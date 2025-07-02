<?php
session_start();
// Verificar sesión (ajusta según tu implementación)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

/* / Conexión a PostgreSQL (ajusta con tus credenciales)
$dbconn = pg_connect("host=localhost dbname=tu_db user=tu_usuario password=tu_contraseña")
    or die('No se ha podido conectar: ' . pg_last_error()); */
    require_once 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Responsivo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Menú vertical */
        .sidebar {
            width: 250px;
            background: var(--dark-color);
            color: white;
            transition: all 0.3s;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            background: var(--primary-color);
            text-align: center;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu h3 {
            color: var(--light-color);
            font-size: 14px;
            padding: 15px 20px;
            text-transform: uppercase;
        }

        .sidebar-menu li {
            list-style: none;
            padding: 10px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }

        .sidebar-menu li:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--primary-color);
        }

        .sidebar-menu li.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--primary-color);
        }

        .sidebar-menu a {
            color: var(--light-color);
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar-menu a i {
            margin-right: 10px;
            font-size: 18px;
        }

        .sidebar-menu .dropdown-menu {
            padding-left: 30px;
            display: none;
        }

        .sidebar-menu .dropdown-menu.show {
            display: block;
        }

        .sidebar-menu .has-dropdown > a::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s;
        }

        .sidebar-menu .has-dropdown.active > a::after {
            transform: rotate(180deg);
        }

        /* Contenido principal */
        .main-content {
            flex: 1;
            margin-left: 250px;
            transition: all 0.3s;
        }

        /* Barra superior */
        .top-navbar {
            background: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .menu-toggle {
            font-size: 20px;
            cursor: pointer;
            display: none;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        /* Tarjetas */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-header h2 {
            font-size: 18px;
            color: var(--dark-color);
        }

        .card-header i {
            font-size: 24px;
            color: var(--primary-color);
        }

        .card-body h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .card-body p {
            color: #666;
            font-size: 14px;
        }

        .card.success {
            border-top: 4px solid var(--success-color);
        }

        .card.warning {
            border-top: 4px solid var(--warning-color);
        }

        .card.danger {
            border-top: 4px solid var(--danger-color);
        }

        /* Tablas */
        .content-area {
            padding: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
        }

        tr:hover {
            background: rgba(52, 152, 219, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Menú vertical -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Mi Dashboard</h2>
            </div>
            <ul class="sidebar-menu">
                <li class="active">
                    <a href="#"><i class="fas fa-home"></i> Inicio</a>
                </li>
                <li class="has-dropdown">
                    <a href="#"><i class="fas fa-user"></i> Usuarios <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Listar Usuarios</a></li>
                        <li><a href="#">Agregar Usuario</a></li>
                        <li><a href="#">Permisos</a></li>
                    </ul>
                </li>
                <li class="has-dropdown">
                    <a href="#"><i class="fas fa-shopping-cart"></i> Productos <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Inventario</a></li>
                        <li><a href="#">Categorías</a></li>
                        <li><a href="#">Proveedores</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="fas fa-chart-line"></i> Reportes</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-cog"></i> Configuración</a>
                </li>
                <li>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
                </li>
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Barra superior -->
            <div class="top-navbar">
                <div class="menu-toggle" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="user-info">
                    <img src="https://via.placeholder.com/40" alt="Usuario">
                    <span><?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?></span>
                </div>
            </div>

            <!-- Contenido -->
            <div class="content-area">
                <h1>Bienvenido al Dashboard</h1>
                <p>Resumen de actividades y estadísticas</p>

                <!-- Tarjetas de resumen -->
                <div class="cards">
                    <div class="card">
                        <div class="card-header">
                            <h2>Usuarios</h2>
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-body">
                           
                            <p>Usuarios registrados</p>
                        </div>
                    </div>

                    <div class="card success">
                        <div class="card-header">
                            <h2>Ventas</h2>
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="card-body">
                        
                            
                            <h1></h1>
                            <p>Ventas hoy</p>
                        </div>
                    </div>

                    <div class="card warning">
                        <div class="card-header">
                            <h2>Productos</h2>
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="card-body">
                            
                           
                            <h1></h1>
                            <p>Productos con bajo stock</p>
                        </div>
                    </div>

                    <div class="card danger">
                        <div class="card-header">
                            <h2>Tareas</h2>
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="card-body">
                           
                            <h1></h1>
                            <p>Tareas pendientes</p>
                        </div>
                    </div>
                </div>

                <!-- Tabla de ejemplo -->
                <div class="table-responsive">
                    <h2>Últimas actividades</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM actividades ORDER BY fecha DESC LIMIT 5";
                            $result = pg_query($dbconn, $query);
                            
                            while ($row = pg_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['usuario']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['accion']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                                echo "<td><span class='badge'>" . htmlspecialchars($row['estado']) . "</span></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Menú desplegable
        document.querySelectorAll('.has-dropdown > a').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                const dropdown = parent.querySelector('.dropdown-menu');
                
                // Cerrar otros dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(d => {
                    if (d !== dropdown) {
                        d.classList.remove('show');
                        d.parentElement.classList.remove('active');
                    }
                });
                
                // Alternar el actual
                dropdown.classList.toggle('show');
                parent.classList.toggle('active');
            });
        });

        // Menú responsive
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Cerrar menú al hacer clic fuera en móviles
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const menuToggle = document.getElementById('menu-toggle');
                
                if (!sidebar.contains(e.target) && e.target !== menuToggle && !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
<?php
// Cerrar conexión
pg_close($dbconn);
?>