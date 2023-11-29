<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            {{-- <i class="fas fa-laugh-wink"></i> --}}
            {{-- <i class="fas fa-sharp fa-solid fa-venus"></i> --}}
            <i class="fas fa-solid fa-chess-queen"></i>
        </div>
        <div class="sidebar-brand-text mx-3">TABULATION <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Navigations
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBarangays"
            aria-expanded="true" aria-controls="collapseBarangays">
            <i class="fas fa fa-location-arrow"></i>
            {{-- <span>Barangays ({{ \App\Models\Barangay::get()->count(); }})</span> --}}
        </a>
        <div id="collapseBarangays" class="collapse" aria-labelledby="headingBarangays" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Barangays:</h6>
                <a class="collapse-item" href="{{ route('barangays.create') }}">Add New</a>
                <a class="collapse-item" href="{{ route('barangays.index') }}">View All</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCandidates"
            aria-expanded="true" aria-controls="collapseCandidates">
            <i class="fas fa-fw fa-users"></i>
            <span>Candidates ({{ \App\Models\Ms_candidate::get()->count(); }})</span>
        </a>
        <div id="collapseCandidates" class="collapse" aria-labelledby="headingCandidates" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Candidates:</h6>
                <a class="collapse-item" href="{{ route('candidates.create') }}">Add New</a>
                <a class="collapse-item" href="{{ route('candidates.index') }}">View All</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseJudges"
            aria-expanded="true" aria-controls="collapseJudges">
            <i class="fas fa-fw fa-users"></i>
            <span>Judges ({{ \App\Models\User::where('role','judge')->get()->count(); }})</span>
        </a>
        <div id="collapseJudges" class="collapse" aria-labelledby="headingJudges" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Judges:</h6>
                <a class="collapse-item" href="{{ route('judges.create') }}">Add New</a>
                <a class="collapse-item" href="{{ route('judges.index') }}">View All</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCategories"
            aria-expanded="true" aria-controls="collapseCategories">
            <i class="fas fa-fw fa-cogs"></i>
            {{-- <span>Categories ({{ \App\Models\Category::get()->count(); }})</span> --}}
        </a>
        <div id="collapseCategories" class="collapse" aria-labelledby="headingCategories" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Categories:</h6>
                <a class="collapse-item" href="{{ route('categories.create') }}">Add New</a>
                <a class="collapse-item" href="{{ route('categories.index') }}">View All</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReportsMs"
            aria-expanded="true" aria-controls="collapseReports">
            <i class="fas fa-fw fa-file"></i>
            <span>Reports (Ms. UEP)</span>
        </a>
        <div id="collapseReportsMs" class="collapse" aria-labelledby="headingReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Reports:</h6>
                <a class="collapse-item" href="{{ route('ms_prepageant') }}">Pre-pageant Scores</a>
                <a class="collapse-item" href="{{ route('ms_prod_num') }}">Production Number Scores</a>
                <a class="collapse-item" href="{{ route('ms_sports_wear') }}">Sports Wear Scores</a>
                <a class="collapse-item" href="{{ route('ms_talent') }}">Talent Scores</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReportsMr"
            aria-expanded="true" aria-controls="collapseReports">
            <i class="fas fa-fw fa-file"></i>
            <span>Reports (Mr. UEP)</span>
        </a>
        <div id="collapseReportsMr" class="collapse" aria-labelledby="headingReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Manage Reports:</h6>
                <a class="collapse-item" href="{{ route('mr_prepageant') }}">Pre-pageant Scores</a>
                <a class="collapse-item" href="{{ route('mr_prod_num') }}">Production Number Scores</a>
                <a class="collapse-item" href="{{ route('mr_sports_wear') }}">Sports Wear Scores</a>
                <a class="collapse-item" href="{{ route('mr_talent') }}">Talent Scores</a>
            </div>
        </div>
    </li>

    {{-- <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Preliminaries</span></a>
    </li> --}}

    <li class="nav-item">
        <a class="nav-link" href="{{ route('settings') }}">
            <i class="fas fa-fw fa-cogs"></i>
            <span>Settings</span></a>
    </li>



</ul>
