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

    public function mr_casual_wear()
    {
        $data['title'] = 'Mr. UEP - Casual Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.casual_wear.casual_wear', compact('data'));
    }

    public function mr_pdfcasual_wear()
    {
        $data['title'] = 'Mr. UEP - Formal Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.casual_wear.pdfcasual_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_casual_wear_result.pdf');
    }

    public function mr_casual_wearjudge1()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.casual_wear.casual_wearjudge1', compact('data'));
    }

    public function mr_pdfcasual_wearjudge1()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.casual_wear.pdfcasual_wearjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_casual_wear_judge1.pdf');
    }

    public function mr_casual_wearjudge2()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.casual_wear.casual_wearjudge2', compact('data'));
    }

    public function mr_pdfcasual_wearjudge2()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.casual_wear.pdfcasual_wearjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_casual_wear_judge2.pdf');
    }

    public function mr_casual_wearjudge3()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.casual_wear.casual_wearjudge3', compact('data'));
    }

    public function mr_pdfcasual_wearjudge3()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.casual_wear.pdfcasual_wearjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_casual_wear_judge3.pdf');
    }

    public function mr_casual_wearjudge4()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 4)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.casual_wear.casual_wearjudge4', compact('data'));
    }

    public function mr_pdfcasual_wearjudge4()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 4)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.casual_wear.pdfcasual_wearjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_casual_wear_judge4.pdf');
    }

    public function mr_casual_wearjudge5()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 5)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.casual_wear.casual_wearjudge5', compact('data'));
    }

    public function mr_pdfcasual_wearjudge5()
    {

        $data['title'] = 'Mr. UEP - Casual Wear Results (Judge 5)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.casual_wear.pdfcasual_wearjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_casual_wear_judge5.pdf');
    }

    public function mr_formal_wear()
    {
        $data['title'] = 'Mr. UEP - Formal Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wear', compact('data'));
    }

    public function mr_pdfformal_wear()
    {
        $data['title'] = 'Mr. UEP - Formal Wear Results';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.formal_wear.pdfformal_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_formal_wear_result.pdf');
    }

    public function mr_formal_wearjudge1()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wearjudge1', compact('data'));
    }

    public function mr_pdfformal_wearjudge1()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 1)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.formal_wear.pdfformal_wearjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_formal_wear_judge1.pdf');
    }

    public function mr_formal_wearjudge2()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wearjudge2', compact('data'));
    }

    public function mr_pdfformal_wearjudge2()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 2)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.formal_wear.pdfformal_wearjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_formal_wear_judge2.pdf');
    }

    public function mr_formal_wearjudge3()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wearjudge3', compact('data'));
    }

    public function mr_pdfformal_wearjudge3()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 3)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.formal_wear.pdfformal_wearjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_formal_wear_judge3.pdf');
    }

    public function mr_formal_wearjudge4()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wearjudge4', compact('data'));
    }

    public function mr_pdfformal_wearjudge4()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.formal_wear.pdfformal_wearjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_formal_wear_judge4.pdf');
    }

    public function mr_formal_wearjudge5()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        return view('admin.reports.prelim.mr.formal_wear.formal_wearjudge5', compact('data'));
    }

    public function mr_pdfformal_wearjudge5()
    {

        $data['title'] = 'Mr. UEP - Formal Wear Results (Judge 5)';

        $data['candidate'] = Mr_candidate::all();

        $data['rank'] = Mr_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.mr.formal_wear.pdfformal_wearjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('mr_formal_wear_judge5.pdf');
    }
}
