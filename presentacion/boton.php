<a href="?pid=<?php echo base64_encode("presentacion/inicio.php") ?>" class="btn btn-flotante">
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
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
    }

    .btn-flotante:hover {
        background-color: #5c0a63;
        transform: scale(1.1) translateY(-3px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
    }

    .btn-flotante:active {
        transform: scale(0.95);
    }

    .btn-flotante i {
        pointer-events: none;
        transition: transform 0.2s ease;
    }

    .btn-flotante:hover i {
        transform: scale(1.1);
    }
</style>