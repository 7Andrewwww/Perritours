<?php
require_once("persistencia/Conexion.php");
require_once("persistencia/PaseadorDAO.php");
require_once("persistencia/PaseoDAO.php");
require_once("persistencia/FacturaDAO.php");

class EstadisticasDueño {
    private $idDueño;
    private $conexion;
    
    public function __construct($idDueño) {
        $this->idDueño = $idDueño;
        $this->conexion = new Conexion();
    }
    
    public function obtenerEstadisticas() {
        return [
            'total_perros' => $this->contarPerros(),
            'total_paseos' => $this->contarPaseos(),
            'gasto_total' => $this->calcularGastoTotal(),
            'paseador_favorito' => $this->obtenerPaseadorFavorito(),
            'paseos_mes' => $this->paseosPorMes(),
            'gastos_mes' => $this->gastosPorMes(),
            'paseadores_frecuentes' => $this->paseadoresFrecuentes()
        ];
    }
    
    private function contarPerros() {
        $this->conexion->abrir();
        $query = "SELECT COUNT(*) FROM perro WHERE id_dueño = " . $this->idDueño;
        $this->conexion->ejecutar($query);
        $resultado = $this->conexion->registro()[0];
        $this->conexion->cerrar();
        return $resultado;
    }
    
    private function contarPaseos() {
        $this->conexion->abrir();
        $query = "SELECT COUNT(DISTINCT pp.id_paseo)
                  FROM paseo_perro pp
                  JOIN perro p ON pp.id_perro = p.id_perro
                  WHERE p.id_dueño = " . $this->idDueño;
        $this->conexion->ejecutar($query);
        $resultado = $this->conexion->registro()[0];
        $this->conexion->cerrar();
        return $resultado;
    }
    
    private function calcularGastoTotal() {
        $this->conexion->abrir();
        $query = "SELECT SUM(f.valor)
                  FROM factura f
                  WHERE f.id_paseo IN (
                      SELECT pp.id_paseo
                      FROM paseo_perro pp
                      JOIN perro p ON pp.id_perro = p.id_perro
                      WHERE p.id_dueño = " . $this->idDueño . "
                  )";
        $this->conexion->ejecutar($query);
        $resultado = $this->conexion->registro()[0] ?? 0;
        $this->conexion->cerrar();
        return $resultado;
    }
    
    private function obtenerPaseadorFavorito() {
        $this->conexion->abrir();
        $query = "SELECT ps.nombre
                  FROM paseador ps
                  JOIN paseo p ON ps.id_pas = p.id_pas
                  JOIN paseo_perro pp ON p.id_paseo = pp.id_paseo
                  JOIN perro pe ON pp.id_perro = pe.id_perro
                  WHERE pe.id_dueño = " . $this->idDueño . "
                  GROUP BY ps.id_pas
                  ORDER BY COUNT(*) DESC
                  LIMIT 1";
        $this->conexion->ejecutar($query);
        $resultado = $this->conexion->registro()[0] ?? null;
        $this->conexion->cerrar();
        return $resultado;
    }
    
    private function paseosPorMes() {
        $this->conexion->abrir();
        $query = "SELECT
                    MONTHNAME(p.fecha) AS mes,
                    COUNT(*) AS cantidad
                  FROM paseo p
                  JOIN paseo_perro pp ON p.id_paseo = pp.id_paseo
                  JOIN perro pe ON pp.id_perro = pe.id_perro
                  WHERE pe.id_dueño = " . $this->idDueño . "
                  GROUP BY MONTH(p.fecha)
                  ORDER BY MONTH(p.fecha)";
        $this->conexion->ejecutar($query);
        
        $resultados = [];
        while ($registro = $this->conexion->registro()) {
            $resultados[] = [
                'mes' => $registro[0],
                'cantidad' => $registro[1]
            ];
        }
        
        $this->conexion->cerrar();
        return $resultados;
    }
    
    private function gastosPorMes() {
        $this->conexion->abrir();
        $query = "SELECT
                    MONTHNAME(f.fecha) AS mes,
                    SUM(f.valor) AS total
                  FROM factura f
                  WHERE f.id_paseo IN (
                      SELECT pp.id_paseo
                      FROM paseo_perro pp
                      JOIN perro p ON pp.id_perro = p.id_perro
                      WHERE p.id_dueño = " . $this->idDueño . "
                  )
                  GROUP BY MONTH(f.fecha)
                  ORDER BY MONTH(f.fecha)";
        $this->conexion->ejecutar($query);
        
        $resultados = [];
        while ($registro = $this->conexion->registro()) {
            $resultados[] = [
                'mes' => $registro[0],
                'total' => $registro[1]
            ];
        }
        
        $this->conexion->cerrar();
        return $resultados;
    }
    
    private function paseadoresFrecuentes() {
        $this->conexion->abrir();
        $query = "SELECT
                    ps.id_pas as id,
                    ps.nombre,
                    ps.foto_url,
                    COUNT(*) AS paseos,
                    AVG(c.puntuacion_paseador) AS calificacion
                  FROM paseador ps
                  JOIN paseo p ON ps.id_pas = p.id_pas
                  JOIN paseo_perro pp ON p.id_paseo = pp.id_paseo
                  JOIN perro pe ON pp.id_perro = pe.id_perro
                  LEFT JOIN calificacion_paseo c ON p.id_paseo = c.id_paseo
                  WHERE pe.id_dueño = " . $this->idDueño . "
                  GROUP BY ps.id_pas
                  ORDER BY paseos DESC
                  LIMIT 5";
        $this->conexion->ejecutar($query);
        
        $resultados = [];
        while ($registro = $this->conexion->registro()) {
            $resultados[] = [
                'id' => $registro[0],
                'nombre' => $registro[1],
                'foto_url' => $registro[2],
                'paseos' => $registro[3],
                'calificacion' => $registro[4] ?? 0
            ];
        }
        
        $this->conexion->cerrar();
        return $resultados;
    }
}