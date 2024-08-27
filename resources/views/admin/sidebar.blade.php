<style>
.center-image-horizontal {
    display: flex;
    justify-content: center;
}

.small-avatar {
    width: 50px;
    height: 50px;
}
</style>
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown mb-3 mt-3">
                <div class="center-image-horizontal">
                    <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle small-avatar">
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="#"><b>Halaman Index</b></a></li>
                    <li><a class="nav-link" href="#}"><b>Input Data</b></a></li>
                    <li><a class="nav-link" href="#"><b>Data Nasabah</b></a></li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-redo"></i><span>Transaksi</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" id="main_transaksi_link" href="#">transaksi</a></li>
                    <li><a class="nav-link" href="#}">Input Data</a></li>
                    <li><a class="nav-link" href="#">Data Nasabah</a></li>
                </ul>
            </li>

        </ul>

    </aside>
</div>
