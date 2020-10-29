<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="stylesheet" href="/css/app.css">
  <link rel="icon" type="image/png" href="/favicon.png">
</head>
<body class="sidebar-mini layout-fixed pace-primary" style="height: auto;">
  <div class="pace pace-inactive">
    <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
      <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
  </div>
  <div class="wrapper" id="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link">
            Control de pagos
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" v-bind:title="$store.state.user.name">
            <i class="far fa-user"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-header">@{{$store.state.user.name}}</span>
            <div class="dropdown-divider"></div>
            <router-link :to="{name:'profile'}" class="dropdown-item">
              <i class="fas fa-id-badge mr-2"></i>
              Perfil
            </router-link>
            <div class="dropdown-divider"></div>
            <a href="#" class="logout dropdown-item dropdown-footer">
              Cerrar sesión
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="/" class="brand-link">
        <img src="https://ui-avatars.com/api/?size=33&name=CPCA" alt="CPCA" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">CPCA</span>
      </a>
      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img v-bind:src="'https://ui-avatars.com/api/?size=34&name='+$store.state.user.name" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">
              @{{$store.state.user.name}}
            </a>
          </div>
        </div>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <router-link class="nav-link" :to="{ name: 'home'}">
                <i class="nav-icon fas fa-th"></i>
                <p>
                  Inicio
                </p>
              </router-link>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                  Configuración
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link class="nav-link" :to="{ name: 'roles.index'}">
                    <i class="fas fa-user-lock nav-icon"></i>
                    <p>
                      Roles
                    </p>
                  </router-link>
                  <router-link class="nav-link" :to="{ name: 'bancos.index'}">
                    <i class="fas fa-money-check-alt nav-icon"></i>
                    <p>
                      Bancos
                    </p>
                  </router-link>
                  <router-link class="nav-link" :to="{ name: 'formapagos.index'}">
                    <i class="fas fa-tags nav-icon"></i>
                    <p>
                      Formas de pago
                    </p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user-shield"></i>
                <p>
                  Sistema
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link class="nav-link" :to="{ name: 'users.index'}">
                    <i class="fas fa-users nav-icon"></i>
                    <p>
                      Usuarios
                    </p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Clientes
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link :to="{name: 'clientes.create'}" class="nav-link">
                    <i class="fas fa-user-plus nav-icon"></i>
                    <p>Crear cliente</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link :to="{name: 'clientes.index'}" class="nav-link">
                    <i class="fas fa-search nav-icon"></i>
                    <p>Buscar cliente</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link :to="{name: 'registrar.consumo'}" class="nav-link">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <p>Registrar consumo</p>
                  </router-link>
                </li>
                <li class="nav-item">
                  <router-link :to="{name: 'registrar.pago'}" class="nav-link">
                    <i class="fas fa-money-bill-alt nav-icon"></i>
                    <p>Registrar pago</p>
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="fas fa-chart-line nav-icon"></i>
                <p>
                  Reportes
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <router-link :to="{name: 'listado.clientes'}" class="nav-link">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Listado de clientes</p>
                  </router-link>
                  <router-link :to="{name: 'clientes.morosos'}" class="nav-link">
                    <i class="fas fa-thumbs-down nav-icon"></i>
                    <p>Clientes morosos</p>
                  </router-link>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </aside>
    <router-view></router-view>
  </div>
  <script src="/js/app.js"></script>
</body>
</html>