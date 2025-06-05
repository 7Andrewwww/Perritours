<a href="../presentacion/Inicio.php" class="btn btn-flotante">
    <i class="fas fa-home"></i>
</a>

<style>
    .btn-flotante {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #8e2ab2;
        color: white;
        font-size: 20px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        transition: background-color 0.3s ease, transform 0.2s ease;
        text-decoration: none;
    }

    .btn-flotante:hover {
        background-color: #5c0a63;
        transform: scale(1.1);
    }

    .btn-flotante i {
        pointer-events: none;
    }
</style>