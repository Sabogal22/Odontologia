<?php
class GestorCita {

public function agregarCita(citas $citas){
    $conexion = new Conexion();
    $conexion->abrir();
    $fecha = $citas->obtenerFecha();
    $hora = $citas->obtenerHora();
    $paciente = $citas->obtenerPaciente();
    $medico = $citas->obtenerMedico();
    $consultorio = $citas->obtenerConsultorio();
    $estado = $citas->obtenerEstado();
    $observaciones = $citas->obtenerObservaciones();
    $sql = "INSERT INTO citas VALUES ( null,'$fecha','$hora',
      '$paciente','$medico','$consultorio','$estado','$observaciones')";
    $conexion->consulta($sql);
    $citaId = $conexion->obtenerCitaId();
    $conexion->cerrar();
    return $citaId;
}

public function consultarCitaPorId($id){
  $conexion = new Conexion();
  $conexion->abrir();
  $sql = "SELECT pacientes.* , medicos.*, consultorios.*, citas.* "
   ."FROM pacientes, medicos, consultorios, citas"
   ." WHERE citas.CitPaciente   = pacientes.PacIdentificacion " 
   ." AND citas.CitMedico       = medicos.MedIdentificacion "
   . "AND citas.CitConsultorio  = consultorios.ConNumero "
   . "AND citas.CitNumero       = $id";
  $conexion->consulta($sql);
  $result = $conexion->obtenerResult();
  $conexion->cerrar();
  return $result;
}

public function consultarCitasPorDocumento($doc){
  $conexion = new Conexion();
  $conexion->abrir();
  $sql = "SELECT * FROM citas"
       . " WHERE CitPaciente = '$doc'"
       . " AND CitEstado = 'Solicitada'";
  $conexion->consulta($sql);
  $result = $conexion->obtenerResult();
  $conexion->cerrar();
  return $result;
}

public function consultarPaciente($doc){
  $conexion = new Conexion();
  $conexion->abrir();
  $sql = "SELECT * FROM Pacientes WHERE PacIdentificacion = '$doc' ";
  $conexion->consulta($sql);
  $result = $conexion->obtenerResult();
  $conexion->cerrar();
  return $result;
}

public function agregarPaciente(Paciente $paciente){
  $conexion = new Conexion();
  $conexion->abrir();
  $identificacion = $paciente->obtenerIdentificacion();
  $nombres = $paciente->obtenerNombres();
  $apellidos = $paciente->obtenerApellidos();
  $fechaNacimiento = $paciente->obtenerFechaNacimiento();
  $sexo = $paciente->obtenerSexo();
  $sql = "INSERT INTO Pacientes VALUES ('$identificacion','$nombres','$apellidos',". "'$fechaNacimiento','$sexo')";
  $conexion->consulta($sql);
  $filasAfectadas = $conexion->obtenerFilasAfectadas();
  $conexion->cerrar();
  return $filasAfectadas;
}

public function consultarMedicos(){
  $conexion = new Conexion();
  $conexion->abrir();
  $sql = "SELECT * FROM Medicos";
  $conexion->consulta($sql);
  $result = $conexion->obtenerResult();
  $conexion->cerrar();
  return $result;
}

public function consultarHorasDisponibles($medico, $fecha){
 $conexion = new Conexion();
 $conexion->abrir();
 $sql = " SELECT hora FROM horas WHERE hora NOT IN "
 . "(SELECT CitHora FROM citas WHERE CitMedico = '$medico' AND CitFecha = '$fecha'"
 . " AND CitEstado = 'Solicitada')"; 
 $conexion->consulta($sql);
 $result = $conexion->obtenerResult();
 $conexion->cerrar();
 return $result;
 }

public function consultarConsultorios(){
  $conexion = new Conexion();
  $conexion->abrir();
  $sql = "SELECT * FROM consultorios";
  $conexion->consulta($sql);
  $result = $conexion->obtenerResult();
  $conexion->cerrar();
  return $result;
}

public function cancelarCita($cita){
  $conexion = new Conexion();
  $conexion->abrir();
  $sql = "UPDATE citas SET CitEstado = 'Cancelada' "
       . " WHERE CitNumero = $cita";
  $conexion->consulta($sql);
  $filasAfectadas = $conexion->obtenerFilasAfectadas();
  $conexion->cerrar();
  return $filasAfectadas;
}

}

?>