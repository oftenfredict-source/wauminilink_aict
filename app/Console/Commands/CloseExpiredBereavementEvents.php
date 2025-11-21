<?php

namespace App\Console\Commands;

use App\Models\BereavementEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloseExpiredBereavementEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bereavement:close-expired {--dry-run : Run without actually closing events}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close bereavement events that have passed their contribution deadline';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired bereavement events...');
        
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN MODE - Events will not be closed');
        }

        // Get all open events that have passed their contribution end date
        $expiredEvents = BereavementEvent::expired()->get();

        $this->info("Found {$expiredEvents->count()} expired event(s)");

        if ($expiredEvents->isEmpty()) {
            $this->info('✅ No expired events to close');
            return 0;
        }

        $closedCount = 0;
        $eventsClosed = [];

        foreach ($expiredEvents as $event) {
            $this->line("Event: {$event->deceased_name} (ID: {$event->id})");
            $this->line("  Contribution deadline: {$event->contribution_end_date->format('Y-m-d')}");
            $this->line("  Total contributions: " . number_format($event->total_contributions, 2));
            $this->line("  Contributors: {$event->contributors_count}");
            
            if (!$dryRun) {
                try {
                    $event->close();
                    $closedCount++;
                    $eventsClosed[] = [
                        'id' => $event->id,
                        'deceased_name' => $event->deceased_name,
                        'total_contributions' => $event->total_contributions,
                        'contributors_count' => $event->contributors_count,
                    ];
                    
                    Log::info("Bereavement event closed automatically", [
                        'event_id' => $event->id,
                        'deceased_name' => $event->deceased_name,
                        'contribution_end_date' => $event->contribution_end_date->format('Y-m-d'),
                        'total_contributions' => $event->total_contributions,
                        'contributors_count' => $event->contributors_count,
                    ]);
                    
                    $this->info("  ✅ Closed successfully");
                } catch (\Exception $e) {
                    $this->error("  ❌ Failed to close: " . $e->getMessage());
                    Log::error("Failed to close bereavement event", [
                        'event_id' => $event->id,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                $this->line("  Would close this event");
                $closedCount++;
            }
        }

        if ($closedCount > 0) {
            $this->info("✅ Processed {$closedCount} expired event(s)");
            
            if (!$dryRun && !empty($eventsClosed)) {
                $this->table(
                    ['ID', 'Deceased Name', 'Total Contributions', 'Contributors'],
                    collect($eventsClosed)->map(function($event) {
                        return [
                            $event['id'],
                            $event['deceased_name'],
                            number_format($event['total_contributions'], 2),
                            $event['contributors_count'],
                        ];
                    })
                );
            }
        }

        $this->info('Bereavement event closure check completed.');
        return 0;
    }
}
