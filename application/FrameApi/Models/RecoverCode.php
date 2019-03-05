<?php
namespace FrameApi\Models;


use FrameApi\DB\Connection;
use PDO;

class RecoverCode extends MainModel
{
	private $id_user;

	private $code;

	private $date_added;

	protected static $primaryKey = 'id_user';


	/**
	 * Variable que guarda una Instancia de User
	 * @var User
	 */
	protected $user;


	/**
	 * Nombre de la tabla en la DB.
	 * @var string
	 */
	protected static $table = 'recover_code';


	public function __construct($user_id)
	{
		$this->setIdUser($user_id);
		$this->setCode($this->generateRecoverCode());
	}


	public function generateRecoverCode($length = 6)
	{

		// Generamos el código
		$chars = 'ABCDEFGHJKMNPQRSTUVWXYZ2356789';

		$charactersLength = strlen($chars);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $chars[rand(0, $charactersLength - 1)];
		}

		// chequeamos que no tenga uno generado anteriormente.
		$exists = self::getUserCode($this->getIdUser());
		// si ya tiene lo borramos.
		if ($exists) self::deleteCode($this->getIdUser());

		// guardamos el código nuevo.
		$query = "
			INSERT INTO " .self::$table . " (id_user, code, date_added)
			VALUES (:id_user, :code, :date_added)
		";

		$stmt = Connection::getStatement($query);

		$result = $stmt->execute([ ':id_user' => $this->getIdUser(), ':code' => $randomString, ':date_added' => date('Y-m-d H:i:s', time())]);

		return $result ? $randomString : null;
	}

	public static function getUserCode($user_id)
	{
		$query = "
			SELECT * FROM " .self::$table . "
			WHERE id_user = $user_id
		";

		$stmt = Connection::getStatement($query);
		$stmt->execute();

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data;

	}

	public static function getUserCodeByCode($code)
	{
		$query = "
			SELECT * FROM " .self::$table . "
			WHERE code = '$code'
		";

		$stmt = Connection::getStatement($query);
		$stmt->execute();

		$data = $stmt->fetch(PDO::FETCH_ASSOC);

		return $data;
	}

	public static function deleteCode($id_user)
	{
		$query = "DELETE FROM " .self::$table . " WHERE id_user = :id_user";
		$stmt = Connection::getStatement($query);

		return $stmt->execute([ ':id_user' => $id_user ]);
	}



	/**
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->code;
	}

	/**
	 * @param mixed $code
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}


	/**
	 * @return mixed
	 */
	public function getDateAdded()
	{
		return $this->date_added;
	}

	/**
	 * @param mixed $date_added
	 */
	public function setDateAdded($date_added)
	{
		$this->date_added = $date_added;
	}

	/**
	 * @return array
	 */
	public static function getFk()
	{
		return self::$fk;
	}

	/**
	 * @return mixed
	 */
	public function getIdUser()
	{
		return $this->id_user;
	}

	/**
	 * @param mixed $id_user
	 */
	public function setIdUser($id_user)
	{
		$this->id_user = $id_user;
	}

}