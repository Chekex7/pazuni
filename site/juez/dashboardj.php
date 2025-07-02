<?php
session_start();
require_once 'config/config.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="page-header">
        <h1>Dashboard Principal</h1>
    </div>
    
    <div class="dashboard-grid">
        <!-- Tarjeta 1 -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Usuarios Registrados</h3>
            </div>
            <div class="card-body">
                <?php
                
                ?>
                <p>Total de usuarios</p>
            </div>
        </div>
        
        <!-- Tarjeta 2 -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3>Actividad Reciente</h3>
            </div>
            <div class="card-body">
                <ul class="activity-list">
                   
                </ul>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>