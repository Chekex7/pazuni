<?php
// Inicia sesión (ya lo tienes implementado)
session_start();

// Verifica si el usuario está logueado (ajusta según tu implementación)
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos (ya la tienes)
require_once 'includes/db.php';

// Obtener datos del usuario (ejemplo)
$usuario = $_SESSION['logged_in'];
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
            --color-primario: #3498db;
            --color-secundario: #2980b9;
            --color-fondo: #f5f5f5;
            --color-texto: #333;
            --color-borde: #ddd;
            --color-menu-vertical: #2c3e50;
            --color-menu-horizontal: #34495e;
            --ancho-menu-vertical: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--color-fondo);
            color: var(--color-texto);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Menú horizontal */
        .menu-horizontal {
            background-color: var(--color-menu-horizontal);
            color: white;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Contenedor principal */
        .container {
            display: flex;
            flex: 1;
        }

        /* Menú vertical */
        .menu-vertical {
            background-color: var(--color-menu-vertical);
            color: white;
            width: var(--ancho-menu-vertical);
            min-height: calc(100vh - 60px);
            transition: all 0.3s;
        }

        .menu-vertical ul {
            list-style: none;
        }

        .menu-vertical li a {
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            transition: background-color 0.3s;
        }

        .menu-vertical li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-vertical li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .menu-vertical li.active a {
            background-color: var(--color-primario);
        }

        /* Contenido */
        .contenido {
            flex: 1;
            padding: 20px;
            background-color: white;
            margin: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Tarjetas */
        .tarjetas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .tarjeta {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--color-primario);
        }

        .tarjeta h3 {
            color: var(--color-primario);
            margin-bottom: 10px;
        }

        .tarjeta .valor {
            font-size: 2rem;
            font-weight: bold;
        }

        /* Gráficos/tablas (placeholders) */
        .grafico, .tabla {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .grafico {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9f9f9;
            color: #999;
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--color-borde);
        }

        table th {
            background-color: var(--color-primario);
            color: white;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        /* Botón para móviles */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .menu-vertical {
                position: fixed;
                left: -100%;
                top: 60px;
                bottom: 0;
                z-index: 90;
            }

            .menu-vertical.active {
                left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .container {
                padding-left: 0;
            }

            .contenido {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .tarjetas {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Menú horizontal -->
    <nav class="menu-horizontal">
        <div class="logo">
            <span>Sistema de Justicia y Paz Tipuro</span>
        </div>
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="user-info">
            <img src="https://via.placeholder.com/40" alt="Usuario">
            <span><?php echo htmlspecialchars($usuario); ?></span>
            <a href="logout.php" style="color: white; margin-left: 10px;"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container">
        <!-- Menú vertical -->
        <nav class="menu-vertical" id="menuVertical">
            <ul>
                <li class="active"><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
                <li><a href="#"><i class="fas fa-chart-line"></i> Crear Caso</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Usuarios</a></li>
                <li><a href="#"><i class="fas fa-box"></i> Productos</a></li>
                <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> Ventas</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Configuración</a></li>
                <li><a href="#"><i class="fas fa-question-circle"></i> Ayuda</a></li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="contenido">
            <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?></h1>
            <p>Este es tu panel de control. Desde aquí puedes gestionar tu aplicación.</p>

            <!-- Tarjetas resumen -->
            <div class="tarjetas">
                <div class="tarjeta">
                    <h3>Usuarios</h3>
                    <div class="valor">1,024</div>
                    <div class="variacion"><i class="fas fa-arrow-up text-success"></i> 12% este mes</div>
                </div>
                <div class="tarjeta">
                    <h3>Ventas</h3>
                    <div class="valor">$24,560</div>
                    <div class="variacion"><i class="fas fa-arrow-up text-success"></i> 8% este mes</div>
                </div>
                <div class="tarjeta">
                    <h3>Productos</h3>
                    <div class="valor">356</div>
                    <div class="variacion"><i class="fas fa-arrow-down text-danger"></i> 2% este mes</div>
                </div>
                <div class="tarjeta">
                    <h3>Órdenes</h3>
                    <div class="valor">189</div>
                    <div class="variacion"><i class="fas fa-arrow-up text-success"></i> 15% este mes</div>
                </div>
            </div>

            <!-- Gráfico (placeholder) -->
            <div class="grafico">
                [Área para gráfico estadístico]
            </div>

            <!-- Tabla de últimos registros -->
            <div class="tabla">
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
                        <tr>
                            <td>1</td>
                            <td>Juan Pérez</td>
                            <td>Inició sesión</td>
                            <td>2023-05-15 10:30</td>
                            <td><span class="badge bg-success">Completado</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>María Gómez</td>
                            <td>Actualizó producto</td>
                            <td>2023-05-15 09:45</td>
                            <td><span class="badge bg-success">Completado</span></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Carlos Ruiz</td>
                            <td>Creó nuevo usuario</td>
                            <td>2023-05-14 16:20</td>
                            <td><span class="badge bg-warning">Pendiente</span></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Ana López</td>
                            <td>Eliminó registro</td>
                            <td>2023-05-14 14:10</td>
                            <td><span class="badge bg-danger">Fallido</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // Toggle del menú vertical en móviles
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('menuVertical').classList.toggle('active');
        });

        // Cerrar menú al hacer clic en un enlace (en móviles)
        document.querySelectorAll('.menu-vertical a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    document.getElementById('menuVertical').classList.remove('active');
                }
            });
        });

        // Actualizar datos dinámicos (ejemplo)
        function actualizarDatos() {
            // Aquí podrías hacer llamadas AJAX para actualizar los datos
            console.log("Actualizando datos...");
        }

        // Actualizar cada 30 segundos
        setInterval(actualizarDatos, 30000);
    </script>
</body>
</html>