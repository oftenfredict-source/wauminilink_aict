@extends('layouts.index')

@section('content')
    <div class="container-fluid px-4">
        <div class="card border-0 shadow-sm mb-4 mt-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">General Financial Report</h4>
                        <p class="text-muted small mb-0">Taarifa ya Mapato na Matumizi</p>
                    </div>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('reports.general') }}" class="d-flex gap-2">
                            <select name="fiscal_year" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach($allBudgets->pluck('fiscal_year')->unique() as $year)
                                    <option value="{{ $year }}" {{ $fiscalYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </form>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="printDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="printDropdown">
                                <li>
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="printReport('all')">
                                        <i class="fas fa-file-invoice text-primary me-2"></i> Print Full Report
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="javascript:void(0)" onclick="printReport('mapato')">
                                        <i class="fas fa-plus-circle text-success me-2"></i> Print Mapato Only
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="javascript:void(0)"
                                        onclick="printReport('matumizi')">
                                        <i class="fas fa-minus-circle text-danger me-2"></i> Print Matumizi Only
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Screen Header -->
                <div class="report-header text-center mb-4 d-print-none">
                    <h5 class="fw-bold mb-1">AFRICA INLAND CHURCH TANZANIA-AICT LOCAL CHURCH YA MOSHI</h5>
                    <h6 class="text-uppercase small fw-bold">TAARIFA YA MAPATO NA MATUMIZI KIPINDI JANUARI - DESEMBA,
                        {{ $fiscalYear }}
                    </h6>
                </div>

                <!-- Print Letterhead (Visible only on print) -->
                <div class="d-none d-print-block mb-4">
                    <div
                        style="display: flex; align-items: center; border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px;">
                        <div style="flex: 0 0 100px;">
                            <img src="{{ asset('assets/images/waumini_link_logo.png') }}" alt="AICT Logo"
                                style="height: 80px; width: auto;">
                        </div>
                        <div style="flex: 1; text-align: center;">
                            <h3 style="margin: 0; font-weight: bold; color: #000;">AFRICA INLAND CHURCH TANZANIA (AICT)</h3>
                            <h4 style="margin: 5px 0 0; font-weight: bold;">DIOCESE OF NORTHERN: MOSHI LOCAL CHURCH</h4>
                            <p style="margin: 5px 0 0; font-size: 12px;">P.O. BOX 123, Moshi, Tanzania | Email:
                                moshi@aict.org | Tel: +255 123 456 789</p>
                        </div>
                        <div style="flex: 0 0 120px; text-align: right; font-size: 11px;">
                            <div style="font-weight: bold;">REF: FIN-{{ $fiscalYear }}-{{ date('m') }}</div>
                            <div>Date: {{ date('d/m/Y') }}</div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-bottom: 25px;">
                        <h4
                            style="text-transform: uppercase; font-weight: bold; margin-bottom: 5px; text-decoration: underline;">
                            GENERAL FINANCIAL REPORT - {{ $fiscalYear }}</h4>
                        <div style="font-style: italic;">Taarifa ya Mapato na Matumizi (Januari - Desemba {{ $fiscalYear }})
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-4 px-2 border-0 bg-light rounded-top" id="reportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link active fw-bold text-uppercase py-3 px-4 border-0 border-bottom border-3 border-transparent"
                            id="mapato-tab" data-bs-toggle="tab" data-bs-target="#mapato" type="button" role="tab"
                            aria-controls="mapato" aria-selected="true">
                            A. MAPATO
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link fw-bold text-uppercase py-3 px-4 border-0 border-bottom border-3 border-transparent"
                            id="matumizi-tab" data-bs-toggle="tab" data-bs-target="#matumizi" type="button" role="tab"
                            aria-controls="matumizi" aria-selected="false">
                            B. MATUMIZI
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="reportTabsContent">
                    <!-- Tab A: MAPATO -->
                    <div class="tab-pane fade show active" id="mapato" role="tabpanel" aria-labelledby="mapato-tab">
                        <div class="d-print-block mb-3">
                            <h5 class="fw-bold text-primary border-bottom border-2 pb-2">
                                <i class="fas fa-plus-circle me-2"></i>A. MAPATO (INCOME)
                            </h5>
                        </div>
                        <div class="table-responsive border rounded shadow-sm">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th class="py-3 px-4">Maelezo</th>
                                        <th class="text-end py-3 px-4">Bajeti (TZS)</th>
                                        <th class="text-end py-3 px-4">Halisi (TZS)</th>
                                        <th class="text-end py-3 px-4">Asilimia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Mifuko ya Sinodi -->
                                    <tr class="fw-bold bg-light">
                                        <td colspan="4" class="py-2 px-4">Mifuko ya Sinodi:</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Sadaka</td>
                                        <td class="text-end px-4">{{ number_format($sadakaBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($sadakaHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $sadakaBajeti > 0 ? round(($sadakaHalisi / $sadakaBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Zaka</td>
                                        <td class="text-end px-4">{{ number_format($zakaBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($zakaHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $zakaBajeti > 0 ? round(($zakaHalisi / $zakaBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Shukurani</td>
                                        <td class="text-end px-4">{{ number_format($shukuraniBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($shukuraniHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $shukuraniBajeti > 0 ? round(($shukuraniHalisi / $shukuraniBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Sunday School</td>
                                        <td class="text-end px-4">{{ number_format($sundaySchoolBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($sundaySchoolHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $sundaySchoolBajeti > 0 ? round(($sundaySchoolHalisi / $sundaySchoolBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Mavuno/Malimbuko</td>
                                        <td class="text-end px-4">{{ number_format($mavunoBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($mavunoHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $mavunoBajeti > 0 ? round(($mavunoHalisi / $mavunoBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Ushirika Mtakatifu</td>
                                        <td class="text-end px-4">{{ number_format($ushirikaMtakatifuBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($ushirikaMtakatifuHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $ushirikaMtakatifuBajeti > 0 ? round(($ushirikaMtakatifuHalisi / $ushirikaMtakatifuBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr class="fw-bold bg-blue-50">
                                        <td class="px-4">Jumla Mifuko ya Sinodi (A)</td>
                                        <td class="text-end px-4">{{ number_format($mifukoYaSinodiBajetiTotal, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($mifukoYaSinodiHalisiTotal, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $mifukoYaSinodiBajetiTotal > 0 ? round(($mifukoYaSinodiHalisiTotal / $mifukoYaSinodiBajetiTotal) * 100) : 0 }}%
                                        </td>
                                    </tr>

                                    <!-- Matoleo Mengine -->
                                    <tr class="fw-bold bg-light">
                                        <td colspan="4" class="py-2 px-4">Matoleo Mengine:</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Talanta</td>
                                        <td class="text-end px-4">{{ number_format($talantaBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($talantaHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $talantaBajeti > 0 ? round(($talantaHalisi / $talantaBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Huduma kwa Mchungaji</td>
                                        <td class="text-end px-4">{{ number_format($mchungajiBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($mchungajiHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $mchungajiBajeti > 0 ? round(($mchungajiHalisi / $mchungajiBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Fungu/Ada ya mkristo</td>
                                        <td class="text-end px-4">{{ number_format($funguAdaBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($funguAdaHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $funguAdaBajeti > 0 ? round(($funguAdaHalisi / $funguAdaBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr class="fw-bold bg-blue-50">
                                        <td class="px-4">Jumla Matoleo Mengine (B)</td>
                                        <td class="text-end px-4">{{ number_format($matoleoMengineBajetiTotal, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($matoleoMengineHalisiTotal, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $matoleoMengineBajetiTotal > 0 ? round(($matoleoMengineHalisiTotal / $matoleoMengineBajetiTotal) * 100) : 0 }}%
                                        </td>
                                    </tr>

                                    <!-- Machangizo Mbalimbali -->
                                    <tr class="fw-bold bg-light">
                                        <td colspan="4" class="py-2 px-4">Machangizo Mbalimbali:</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Sherehe za Pasaka</td>
                                        <td class="text-end px-4">{{ number_format($pasakaBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($pasakaHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $pasakaBajeti > 0 ? round(($pasakaHalisi / $pasakaBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Sherehe za Krismasi</td>
                                        <td class="text-end px-4">{{ number_format($krisimasiBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($krisimasiHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $krisimasiBajeti > 0 ? round(($krisimasiHalisi / $krisimasiBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Ujenzi wa Kanisa</td>
                                        <td class="text-end px-4">{{ number_format($ujenziBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($ujenziHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $ujenziBajeti > 0 ? round(($ujenziHalisi / $ujenziBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Ununuzi wa Tank</td>
                                        <td class="text-end px-4">{{ number_format($tankBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($tankHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $tankBajeti > 0 ? round(($tankHalisi / $tankBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Ujenzi wa Jengo la makao makuu</td>
                                        <td class="text-end px-4">{{ number_format($makaoMakuuBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($makaoMakuuHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $makaoMakuuBajeti > 0 ? round(($makaoMakuuHalisi / $makaoMakuuBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr class="fw-bold bg-blue-50">
                                        <td class="px-4">Jumla Machangizo (C)</td>
                                        <td class="text-end px-4">{{ number_format($machangizoBajetiTotal, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($machangizoHalisiTotal, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $machangizoBajetiTotal > 0 ? round(($machangizoHalisiTotal / $machangizoBajetiTotal) * 100) : 0 }}%
                                        </td>
                                    </tr>

                                    <tr class="fw-bold border-top border-dark">
                                        <td class="px-4">Jumla Kuu Mapato (A+B+C)</td>
                                        <td class="text-end px-4">{{ number_format($totalMapatoBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($totalMapatoHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $totalMapatoBajeti > 0 ? round(($totalMapatoHalisi / $totalMapatoBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab B: MATUMIZI -->
                    <div class="tab-pane fade" id="matumizi" role="tabpanel" aria-labelledby="matumizi-tab">
                        <div class="d-print-block mb-3">
                            <h5 class="fw-bold text-danger border-bottom border-2 pb-2">
                                <i class="fas fa-minus-circle me-2"></i>B. MATUMIZI (EXPENDITURE)
                            </h5>
                        </div>
                        <div class="table-responsive border rounded shadow-sm">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="bg-danger text-white border-danger">
                                    <tr>
                                        <th class="py-3 px-4">Maelezo</th>
                                        <th class="text-end py-3 px-4">Bajeti (TZS)</th>
                                        <th class="text-end py-3 px-4">Halisi (TZS)</th>
                                        <th class="text-end py-3 px-4">Asilimia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- (A) Mawasilisho -->
                                    <tr class="fw-bold bg-light">
                                        <td colspan="4" class="py-2 px-4">Mawasilisho:</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Pastoreti(kutoka Local Church)</td>
                                        <td class="text-end px-4">{{ number_format($pastoretiBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($pastoretiHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $pastoretiBajeti > 0 ? round(($pastoretiHalisi / $pastoretiBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr class="fw-bold bg-red-50">
                                        <td class="px-4">Jumla Mawasilisho (A)</td>
                                        <td class="text-end px-4">{{ number_format($mawasilishoBajetiTotal, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($mawasilishoHalisiTotal, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $mawasilishoBajetiTotal > 0 ? round(($mawasilishoHalisiTotal / $mawasilishoBajetiTotal) * 100) : 0 }}%
                                        </td>
                                    </tr>

                                    <!-- (B) Posho -->
                                    <tr class="fw-bold bg-light">
                                        <td colspan="4" class="py-2 px-4">Posho:</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4">Posho kwa Watumishi - (B)</td>
                                        <td class="text-end px-4">{{ number_format($poshoBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($poshoHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $poshoBajeti > 0 ? round(($poshoHalisi / $poshoBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>

                                    <!-- (C) Matumizi Mengine -->
                                    <tr class="fw-bold bg-light">
                                        <td colspan="4" class="py-2 px-4">Matumizi Mengine:</td>
                                    </tr>
                                    @foreach($items_c as $key => $name)
                                        <tr>
                                            <td class="px-4">{{ $name }}</td>
                                            <td class="text-end px-4">{{ number_format($matumiziMengineBajeti[$key], 2) }}</td>
                                            <td class="text-end px-4">{{ number_format($matumiziMengineHalisi[$key], 2) }}</td>
                                            <td class="text-end px-4">
                                                {{ $matumiziMengineBajeti[$key] > 0 ? round(($matumiziMengineHalisi[$key] / $matumiziMengineBajeti[$key]) * 100) : 0 }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="fw-bold bg-red-50">
                                        <td class="px-4">Jumla Matumizi (C)</td>
                                        <td class="text-end px-4">{{ number_format($matumiziMengineBajetiTotal, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($matumiziMengineHalisiTotal, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $matumiziMengineBajetiTotal > 0 ? round(($matumiziMengineHalisiTotal / $matumiziMengineBajetiTotal) * 100) : 0 }}%
                                        </td>
                                    </tr>

                                    <tr class="fw-bold border-top border-dark">
                                        <td class="px-4">Jumla Matumizi (A+B+C)</td>
                                        <td class="text-end px-4">{{ number_format($totalMatumiziBajeti, 2) }}</td>
                                        <td class="text-end px-4">{{ number_format($totalMatumiziHalisi, 2) }}</td>
                                        <td class="text-end px-4">
                                            {{ $totalMatumiziBajeti > 0 ? round(($totalMatumiziHalisi / $totalMatumiziBajeti) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Signature Section -->
    <div class="d-none d-print-block mt-5">
        <div class="row" style="display: flex; justify-content: space-between; margin-top: 50px;">
            <div style="flex: 0 0 30%; text-align: center;">
                <div style="margin-bottom: 40px; border-bottom: 1px solid #000;"></div>
                <div style="font-weight: bold; color: #000;">Mchungaji Kiongozi</div>
                <div style="font-size: 11px;">(Pastor-in-Charge)</div>
            </div>
            <div style="flex: 0 0 30%; text-align: center;">
                <div style="margin-bottom: 40px; border-bottom: 1px solid #000;"></div>
                <div style="font-weight: bold; color: #000;">Mweka Hazina</div>
                <div style="font-size: 11px;">(Church Treasurer)</div>
            </div>
            <div style="flex: 0 0 30%; text-align: center;">
                <div style="margin-bottom: 40px; border-bottom: 1px solid #000;"></div>
                <div style="font-weight: bold; color: #000;">Katibu wa Kanisa</div>
                <div style="font-size: 11px;">(Church Secretary)</div>
            </div>
        </div>
        <div
            style="margin-top: 60px; text-align: center; border-top: 1px solid #eee; padding-top: 10px; font-size: 10px; color: #666;">
            This report was generated automatically by WauminiLink Financial System on {{ date('M d, Y H:i:s') }}
        </div>
    </div>
    </div>
    </div>
    </div>

    <style>
        @media print {
            @page {
                margin: 1.5cm;
                size: A4;
            }

            /* Reset layout height and overflow for print */
            html,
            body,
            #layoutSidenav,
            #layoutSidenav_content,
            main,
            .container-fluid,
            .card,
            .card-body {
                height: auto !important;
                min-height: 0 !important;
                overflow: visible !important;
                display: block !important;
                position: static !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }

            .btn,
            form,
            nav,
            .nav-tabs,
            footer,
            .sidebar,
            header,
            .sb-topnav,
            #layoutSidenav_nav {
                display: none !important;
            }

            /* Show/Hide sections based on print mode */
            .tab-pane {
                display: none !important;
                opacity: 0 !important;
                visibility: hidden !important;
            }

            /* All mode */
            body.print-all .tab-pane {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                position: static !important;
                height: auto !important;
            }

            /* Mapato only mode */
            body.print-mapato #mapato {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                position: static !important;
                height: auto !important;
            }

            /* Matumizi only mode */
            body.print-matumizi #matumizi {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                position: static !important;
                height: auto !important;
                margin-top: 0 !important;
                /* Remove top margin if it's the only section */
            }

            /* Manage spacing between sections in Print All */
            body.print-all #matumizi {
                margin-top: 50px !important;
                page-break-before: auto !important;
            }

            table {
                font-size: 11px !important;
                width: 100% !important;
                border-collapse: collapse !important;
                color: #000 !important;
                table-layout: auto !important;
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .table-responsive {
                overflow: visible !important;
                border: none !important;
            }

            .table th {
                background-color: #f0f0f0 !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                border: 1px solid #ddd !important;
            }

            .table td {
                border: 1px solid #ddd !important;
            }

            .bg-primary,
            .bg-danger {
                background-color: #f8f9fa !important;
                color: #000 !important;
            }

            .bg-blue-50,
            .bg-red-50,
            .bg-light {
                background-color: #fcfcfc !important;
                font-weight: bold !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .text-white {
                color: #000 !important;
            }
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .bg-blue-50 {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .bg-red-50 {
            background-color: rgba(220, 53, 69, 0.05);
        }
    </style>

    <script>
        function printReport(type) {
            const body = document.body;
            // Remove any existing print classes
            body.classList.remove('print-all', 'print-mapato', 'print-matumizi');

            // Add the selected print class
            body.classList.add('print-' + type);

            // Trigger browser print
            window.print();

            // Helpful cleanup: remove the class after a short delay
            setTimeout(() => {
                body.classList.remove('print-' + type);
            }, 500);
        }
    </script>
@endsection