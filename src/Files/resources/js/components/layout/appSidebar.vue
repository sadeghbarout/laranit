<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <div class="d-flex justify-content-between">
            <div class="brand-link d-flex align-items-center">
                <img src="/images/logo.png" alt="Logo" style="width: 40px; height: 40px;margin: 0 6px;">
                <span class="brand-text font-weight-light px-2">Project Name</span>
            </div>
            <div class="d-flex d-lg-none align-items-center" style="padding: 16px;" data-widget="pushmenu" href="#" role="button">
                <i class="far fa-times" style="color: #a9a9a9;font-size: 20px;"></i>
            </div>
        </div>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img :src="$user.image" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ $user.username }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item"><router-link to="/" class="nav-link"><i class="fas fa-tachometer-alt"></i><p>Dashboard</p></router-link></li>
                    <li class="nav-item"><router-link to="/admin" class="nav-link"><i class="fas fa-user-alt"></i><p>Admins</p></router-link></li>
                    <li class="nav-item"><router-link to="/user" class="nav-link"><i class="fas fa-user-alt"></i><p>Users</p></router-link></li>
                    <li class="nav-item"><router-link to="/setting" class="nav-link"><i class="fas fa-user-cog"></i><p>Setting</p></router-link></li>
                    <li class="nav-item"><a  href="/logout" class="nav-link"><i class="fas fa-power-off"></i><p>Logout</p></a></li>
                </ul>
            </nav>

        </div>
    </aside>
</template>
