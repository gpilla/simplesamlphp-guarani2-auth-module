<?php

require( dirname(__FILE__) . '/password.php' );

class sspmod_guarani_Auth_Source_GuaraniAuth extends sspmod_core_Auth_UserPassBase {

	private $pdo;

	public function __construct($info, $config) {
		parent::__construct($info, $config);
		$dsn = 'informix:client_locale=en_us.cp1252;service=1526;protocol=olsoctcp;EnableScrollableCursors=1';

		$camposConf = array('LogonId', 'pwd', 'server', 'host', 'database');
		
		foreach ($camposConf as $campo) {
			if (!isset($config[$campo])) {
				throw new Exception("No esta configurado el campo $campo");
			}
			$dsn .= ";$campo={$config[$campo]}";
		}
		
		$this->pdo = new PDO($dsn);
	}

	protected function login($username, $password) {

		$sql = "SELECT *
			FROM
				aca_usuarios_ag AS l
				JOIN sga_personas AS p ON (l.nro_inscripcion = p.nro_inscripcion)
				JOIN sga_alumnos AS a ON (l.nro_inscripcion = a.nro_inscripcion)
			WHERE
				identificacion = '$username'";

		$result = $this->pdo->query($sql);
		$datos = $result->fetchAll();

		// Verifico si recupere algun usuario
		if (count($datos) == 0) {
			throw new SimpleSAML_Error_Error('WRONGUSERPASS');
		}

		$alumno = $datos[0];

		// Verifico la clave
                if (!password_verify(md5($password), $alumno['CLAVE'])) {
			throw new SimpleSAML_Error_Error('WRONGUSERPASS');
                }

		$user = array();
		$user['uid'] = array($alumno['NRO_INSCRIPCION']);
		$user['eduPersonPrincipalName'] = array($alumno['NRO_INSCRIPCION']);
		$user['o'] = array($alumno['UNIDAD_ACADEMICA']);
		$user['givenName'] = array($alumno['NOMBRES']);
		$user['sn'] = array($alumno['APELLIDO']);
		$user['mail'] = array($this->getMail($alumno['NRO_INSCRIPCION']));
                $user['cn'] = array($alumno['APELLIDO'].', '.$alumno['NOMBRES']);

		// Para el enrolment
		//$cursos = $this->getCursos($alumno['LEGAJO']);
		$cursos = array();

		$user['schacUserStatus'] = array();
		foreach ($cursos as $curso) {
			$user['schacUserStatus'][] = "urn:mace:terena.org:schac:userStatus:ar:campus.unaj.edu.ar:{$curso['COMISION']}:student:active";
		}

		return $user;
	}

	private function getMail($nro_inscripcion)
	{
		$sql = "SELECT * FROM sga_datos_censales WHERE nro_inscripcion = '$nro_inscripcion' ORDER BY FECHA_ACTUALIZ DESC";
		$result = $this->pdo->query($sql);
		$datos = $result->fetchAll();
		return $datos[0]['E_MAIL'];
	}

	private function getCursos($legajo)
	{
		$sql = "SELECT * FROM sga_insc_cursadas WHERE legajo = '$legajo'";
                $result = $this->pdo->query($sql);
                $datos = $result->fetchAll();
		return $datos;
	}
}
