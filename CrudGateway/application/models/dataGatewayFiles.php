<?php



/**
 * Read users from file
 * @param array $config
 * @throws Exception
 * @return array: $users | FALSE
 */
function readUsers($config)
{
	$userFilename=$config['production']['userFilename'];
	try 
	{
		if (!file_exists($userFilename)) {
			throw new Exception('Imposible Leer Usuarios.');
		}
		$users=readDataFromFile($userFilename);
		
		return $users;
	} 
	catch (Exception $e) 
	{
		echo 'Ha ocurrido el siguiente error: ',  $e->getMessage(), "\n";
		return FALSE;
	}	
}


/**
 * Read id user from file 
 * @param int $id
 * @param array $config
 * @return array $user
 */
function readUser($id, $config)
{
	$userFilename=$config['production']['userFilename'];
	
	$dataArray=readDataFromFile($userFilename);
	$user=$dataArray[$id];
	
	return $user;
}

/**
 * Delete user from file
 * @param int $id
 * @param array $config
 * @return void;
 */
function deleteUser($id,$config)
{
	$uploadDir=$config['production']['uploadDirectory'];
	$userFilename=$config['production']['userFilename'];
	
	$user=readUser($id, $config);
	$users=readUsers($config);
	deleteFile($user[11], $uploadDir);
	unset($users[$_POST['id']]);
	writeDataToFile($userFilename, $users, TRUE);
	
	return;
}



