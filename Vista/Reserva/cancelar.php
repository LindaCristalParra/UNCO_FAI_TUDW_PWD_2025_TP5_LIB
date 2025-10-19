<?php
// Vista para cancelar reserva
include_once(__DIR__ . '/../Estructura/header.php');
?>
<div class="container mt-4">
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link" href="calendario.php">Reservar</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="cancelar.php">Cancelar reserva</a>
        </li>
    </ul>
    <h2>Cancelar Reserva</h2>
    <form method="post" class="mb-3">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
    <button type="submit" class="calendar-dia-btn">Ver mis reservas</button>
    </form>
    <!-- Aquí se mostrarán las reservas del usuario y los botones para cancelar -->
</div>
<?php
include_once(__DIR__ . '/../Estructura/footer.php');
?>