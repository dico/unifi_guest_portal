<?php
namespace GuestPortal\Room;

/**
 * Class description
 *
 * @author Robert Andresen <ra@fosenikt.no>
 */



class Room extends GeneralBackend {

	function __construct()
	{
		parent::__construct();

	}





	public function get_rooms($institution_id)
	{
		$r = array();

		$query = "SELECT * FROM rooms WHERE location_id='$institution_id'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;
		
		while ($row = $result->fetch_array()) {
			$r[] = array(
				'id' => $row['id'],
				'location_id' => $row['location_id'],
				'lastname' => $row['lastname'],
				'room' => $row['room'],
				'pin' => $row['pin'],
			);
		}

		return $r;
	}


	public function get_room($id)
	{
		$r = array();

		$query = "SELECT * FROM rooms WHERE id='$id'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;
		
		while ($row = $result->fetch_array()) {
			$r = array(
				'id' => $row['id'],
				'location_id' => $row['location_id'],
				'lastname' => $row['lastname'],
				'room' => $row['room'],
				'pin' => $row['pin'],
			);
		}

		return $r;
	}





	public function get_my_rooms()
	{

	}





	public function add_room($p)
	{
		$r = array();

		// Loop, generate pin and check for duplicate, to avoid multiple lastname with same pin
		for ($i=0; $i < 10; $i++) { 
			$pin = $this->generate_pin();
			$check_pin = $this->check_pin($p['lastname'], $pin);

			if ($check_pin) break;
		}


		// Insert pasient to room
		$query = "INSERT INTO rooms SET 
					location_id='{$p['location_id']}', 
					lastname='{$p['lastname']}',
					room='{$p['room']}', 
					pin='$pin'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}





	public function edit_room($p)
	{
		$r = array();

		// Insert pasient to room
		$query = "UPDATE rooms SET 
					location_id='{$p['location_id']}', 
					room='{$p['room']}'
				  WHERE id={$p['id']}";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}





	public function delete_room($id)
	{
		$r = array();

		$query = "DELETE FROM rooms WHERE id='$id'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}















	public function get_institutions()
	{
		$r = array();

		$query = "SELECT * FROM room_locations";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;
		
		while ($row = $result->fetch_array()) {
			$r[] = array(
				'id' => $row['id'],
				'name' => $row['name'],
			);
		}

		return $r;
	}

	public function get_institution($id)
	{
		$r = array();

		$query = "SELECT * FROM room_locations WHERE id='$id'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;
		
		while ($row = $result->fetch_array()) {
			$r = array(
				'id' => $row['id'],
				'name' => $row['name'],
			);
		}

		return $r;
	}

	public function add_institution($p)
	{
		$r = array();

		// Insert pasient to room
		$query = "INSERT INTO room_locations SET name='{$p['name']}'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}


	public function edit_institution($p)
	{
		$r = array();

		// Insert pasient to room
		$query = "UPDATE room_locations SET name='{$p['name']}' WHERE id='{$p['id']}'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}



	public function delete_institution($id)
	{
		$r = array();

		$query = "DELETE FROM room_locations WHERE id='$id'";
		$result = $this->mysqli->query($query);

		if ($result) {
			$r['status'] = 'success';
		} else {
			$r['status'] = 'error';
			$r['message'] = 'DB error';
		}

		return $r;
	}
















	private function generate_pin()
	{
		return rand(0001,9999);
	}




	private function check_pin($lastname, $pin)
	{
		$query = "SELECT * FROM patient_rooms WHERE lastname LIKE '$lastname' AND pin='$pin'";
		$result = $this->mysqli->query($query);
		$numRows = $result->num_rows;

		if ($numRows == 0) return true;
		else return false;
	}

}