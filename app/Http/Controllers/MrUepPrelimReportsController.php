<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Score;
use App\Models\Mr_candidate;
use App\Models\Mr_ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MrUepPrelimReportsController extends Controller
{

    public function mr_prelim()
    {
        $data['title'] = 'Preliminaries Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelim', compact('data'));
    }

    public function mr_pdfprelim()
    {
        $data['title'] = 'Preliminaries Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.pdfprelim', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prelim_result.pdf');
    }

    public function mr_prelimjudge1()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 1';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelimjudge1', compact('data'));
    }

    public function mr_pdfprelimjudge1()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 1';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.pdfprelimjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prelim_judge1.pdf');
    }

    public function mr_prelimjudge2()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 2';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelimjudge2', compact('data'));
    }

    public function mr_pdfprelimjudge2()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 2';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.pdfprelimjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prelim_judge2.pdf');
    }

    public function mr_prelimjudge3()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 3';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelimjudge3', compact('data'));
    }

    public function mr_pdfprelimjudge3()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 3';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.pdfprelimjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prelim_judge3.pdf');
    }

    public function mr_prelimjudge4()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 4';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelimjudge4', compact('data'));
    }

    public function mr_pdfprelimjudge4()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 4';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.pdfprelimjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prelim_judge4.pdf');
    }

    public function mr_prelimjudge5()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 5';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.prelimjudge5', compact('data'));
    }

    public function mr_pdfprelimjudge5()
    {

        $data['title'] = 'Mr. UEP Preliminaries Results Judge 5';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.pdfprelimjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prelim_judge5.pdf');
    }

    public function mr_prod_num()
    {
        $data['title'] = 'Mr. UEP - Production Number Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.production_number.prod_num', compact('data'));
    }

    public function mr_pdfprod_num()
    {
        $data['title'] = 'Mr. UEP - Production Number Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.production_number.pdfprod_num', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prod_num_result.pdf');
    }

    public function mr_prod_numjudge1()
    {

        $data['title'] = 'Mr. UEP - Production Number Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.production_number.prod_numjudge1', compact('data'));
    }

    public function mr_pdfprod_numjudge1()
    {

        $data['title'] = 'Mr. UEP - Production Number Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.production_number.pdfprod_numjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_prod_num_judge1.pdf');
    }
}
