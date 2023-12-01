<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Score;
use App\Models\Ms_candidate;
use App\Models\Ms_ranking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class MsUepPrelimReportsController extends Controller
{

    public function ms_prelim()
    {
        $data['title'] = 'Ms. UEP Preliminaries Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelim', compact('data'));
    }

    public function ms_pdfprelim()
    {
        $data['title'] = 'Ms. UEP Preliminaries Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelim', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_result.pdf');
    }

    public function ms_prelimjudge1()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 1';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge1', compact('data'));
    }

    public function ms_pdfprelimjudge1()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 1';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge1.pdf');
    }

    public function ms_prelimjudge2()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 2';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge2', compact('data'));
    }

    public function ms_pdfprelimjudge2()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 2';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge2.pdf');
    }

    public function ms_prelimjudge3()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 3';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge3', compact('data'));
    }

    public function ms_pdfprelimjudge3()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 3';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge3.pdf');
    }

    public function ms_prelimjudge4()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 4';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge4', compact('data'));
    }

    public function ms_pdfprelimjudge4()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 4';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge4.pdf');
    }

    public function ms_prelimjudge5()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 5';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.prelimjudge5', compact('data'));
    }

    public function ms_pdfprelimjudge5()
    {

        $data['title'] = 'Ms. UEP Preliminaries Results Judge 5';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.pdfprelimjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_prelim_judge5.pdf');
    }

    public function ms_casual_wear()
    {
        $data['title'] = 'Ms. UEP - Casual Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wear', compact('data'));
    }

    public function ms_pdfcasual_wear()
    {
        $data['title'] = 'Ms. UEP - Formal Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_result.pdf');
    }

    public function ms_casual_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge1', compact('data'));
    }

    public function ms_pdfcasual_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge1.pdf');
    }

    public function ms_casual_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge2', compact('data'));
    }

    public function ms_pdfcasual_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge2.pdf');
    }

    public function ms_casual_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge3', compact('data'));
    }

    public function ms_pdfcasual_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge3.pdf');
    }

    public function ms_casual_wearjudge4()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge4', compact('data'));
    }

    public function ms_pdfcasual_wearjudge4()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge4.pdf');
    }

    public function ms_casual_wearjudge5()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 5)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.casual_wear.casual_wearjudge5', compact('data'));
    }

    public function ms_pdfcasual_wearjudge5()
    {

        $data['title'] = 'Ms. UEP - Casual Wear Results (Judge 5)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.casual_wear.pdfcasual_wearjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_casual_wear_judge5.pdf');
    }

    public function ms_formal_wear()
    {
        $data['title'] = 'Ms. UEP - Formal Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wear', compact('data'));
    }

    public function ms_pdfformal_wear()
    {
        $data['title'] = 'Ms. UEP - Formal Wear Results';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wear', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_result.pdf');
    }

    public function ms_formal_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge1', compact('data'));
    }

    public function ms_pdfformal_wearjudge1()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 1)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge1', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge1.pdf');
    }

    public function ms_formal_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge2', compact('data'));
    }

    public function ms_pdfformal_wearjudge2()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 2)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge2', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge2.pdf');
    }

    public function ms_formal_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge3', compact('data'));
    }

    public function ms_pdfformal_wearjudge3()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 3)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge3', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge3.pdf');
    }

    public function ms_formal_wearjudge4()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge4', compact('data'));
    }

    public function ms_pdfformal_wearjudge4()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge4', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge4.pdf');
    }

    public function ms_formal_wearjudge5()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 4)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        return view('admin.reports.prelim.ms.formal_wear.formal_wearjudge5', compact('data'));
    }

    public function ms_pdfformal_wearjudge5()
    {

        $data['title'] = 'Ms. UEP - Formal Wear Results (Judge 5)';

        $data['candidate'] = Ms_candidate::all();

        $data['rank'] = Ms_ranking::all();

        $pdf = PDF::loadView('admin.reports.prelim.ms.formal_wear.pdfformal_wearjudge5', compact('data'))->setPaper(array(0, 0, 612, 936), 'landscape');

        return $pdf->stream('ms_formal_wear_judge5.pdf');
    }
}
