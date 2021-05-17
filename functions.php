<?php
	require_once("db-info.php");
	$errors = array(
		'group-nf'=>"Такой группы не существует, проверьте строку адреса, или вернитесь к предыдущей странице.",
		'student-nf' => 'Такого ученика не существует, проверьте строку адреса, или вернитесь к предыдущей странице.',
		'char-on-adress' => 'Нельзя баловаться с параметрами в строке адреса. Исправляй давай.',
		'db-unavailable' => "Сервер базы данных хостинга недоступен.",
		'destroy-session' => "Завершите сессию перед регистрацией.",
		'unaviable-token' => "Недействительный токен."
	);
	function put_error($id, $text){
		return array('error_code'=> $id, 'text'=> $text);
	}
	function db_connect() {
		$link = mysqli_connect(MYSQL_SERVER,MYSQL_USER,MYSQL_PASSWORD,MYSQL_DB);
		mysqli_query($link,"SET NAMES 'utf8mb4'");
		if (!$link) {
		    echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
		    echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
		    echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
		    exit;
		}
		return $link;
	}
	function clear_phone($phone){
		$phone = str_replace('(','',$phone);
		$phone = str_replace(')','',$phone);
		$phone = str_replace('-','',$phone);
		return trim($phone);
	}
	function get_data( $link, $table, $column, $where, $what ) {
		if ( $column == "*" ) {
			$query = "SELECT * FROM `$table` WHERE `$where`='$what'";
		} else {
			$query = "SELECT `$column` FROM `$table` WHERE `$where`=$what";
		}
		$result = mysqli_query( $link, $query );
		if ( !$result ) {
			die( mysqli_error( $link ) );
		}
		$thing = mysqli_fetch_assoc( $result );
		if ( $column == "*" ) {
			return $thing;
		}else{
			return $thing[$column];
		}
	}

	function get_all_data( $link, $table, $column, $where = null, $what = null, $orderby = null , $desc = true, $not = null) {
		if ( $column == "*" ) {
			$query = "SELECT * FROM `$table`";
		} else {
			$query = "SELECT `$column` FROM `$table`";
		}
		if($where != null && $what != null){
			$query.=" WHERE `$where`='$what'";
		}elseif (is_array($where) && $what == null) {
			$query.=" WHERE ";
			foreach ($where as $whe => $wha) {
				$query.="`$whe`='$wha'";
				if($wha != end($where)) $query.=" AND ";
			}
			//exit($query);
		}elseif ($where != null && $not != null) {
			$query.= " WHERE `$where`!=$not";
		}elseif ($where != null && $what != null && $not != null) {
			$query.= " WHERE `$where`='$what' AND $not";
		}
		if ( $orderby != null ){
			$query .= " ORDER BY `$orderby`";
			if ($desc) $query .= "DESC";
		}
		$result = mysqli_query( $link, $query );
		if ( !$result ) {
			die( mysqli_error( $link ) );
		}
		$n = mysqli_num_rows( $result );
		$things = array();

		for ( $i = 0; $i < $n; $i++ ) {
			$row = mysqli_fetch_assoc( $result );
			$things[] = $row;
		}
		return $things;
	}
	function get_more_count($link, $table, $wherewhat){
		$query = "SELECT COUNT(*) FROM `$table` WHERE ".$wherewhat;
		$result = mysqli_query( $link, $query );
		if ( !$result ) {
			die( mysqli_error( $link ) );
		}
		$count = mysqli_fetch_assoc( $result );
		return $count['COUNT(*)'];
	}
	function get_count($link, $table, $where, $what){
		$query = "SELECT COUNT(*) FROM `$table` WHERE `$where`='$what'";
		$result = mysqli_query( $link, $query );
		if ( !$result ) {
			die( mysqli_error( $link ) );
		}
		$count = mysqli_fetch_assoc( $result );
		return $count['COUNT(*)'];
	}
	function get_schedule($link, $gid, $calls){
		$schedule = get_all_data($link, "Schedule", "*", "group-id", $gid, "num-day");
		$dow = date("N");
		$out = array($dow => array());
		for( $i = 1; $i <= 7; $i++){
			if($i != $dow) $out[$i] = array();
		}
		for ($i = 0; $i < count($schedule); $i++) {
			$lesson_info = get_data($link, "Lessons", "*", "id", $schedule[$i]['lesson-id']);
			$schedule[$i]['name'] = $lesson_info['name'];
			$schedule[$i]['active'] = is_active($schedule[$i]['num-lesson'], $calls, $schedule[$i]['num-day']);
			$schedule[$i]['call'] = $calls[$schedule[$i]['num-lesson'] - 1];
			//echo "D:".convert_day($schedule[$i]['num-day'])." N:".$schedule[$i]['num-lesson']." IA: ".(($schedule[$i]['active'])?'+':'-')." | ";
			$teacher = get_data($link, "Teachers", "*", "id", $lesson_info['teacher-id']);
			$schedule[$i]['teacher'] = $teacher;
			switch ($schedule[$i]['num-day']) {
				case '1': array_push($out[1], $schedule[$i]);break;
				case '2': array_push($out[2], $schedule[$i]);break;
				case '3': array_push($out[3], $schedule[$i]);break;
				case '4': array_push($out[4], $schedule[$i]);break;
				case '5': array_push($out[5], $schedule[$i]);break;
				case '6': array_push($out[6], $schedule[$i]);break;
				case '7': array_push($out[7], $schedule[$i]);break;
			}
		}
		foreach ($out as $key => $value) {
			usort($out[$key], "sortby_lesson");
		}
		return $out;
	}
	function calc_diff($time, $gotendings=false){
		$origin =  new DateTime(date("Y-m-d H:i", strtotime($time)));
		$target =  new DateTime();
		$interval = $origin->diff($target);
		$diffmin = $interval->format('%i');

		return ($diffmin > 0) ? $diffmin.num_to_word($diffmin,array(' минута', ' минуты', ' минут')):' меньше минуты';
	}
	function get_calls($link){
		$calls = get_all_data($link, 'Calls','*');
		for ($i=0; $i < count($calls); $i++) {
			$calls[$i]['active'] = is_active($i+1, $calls, date("N"));
		}
		return $calls;
	}
	function is_active($num, $calls, $dow){
		$target_dow = date("N");
		if($dow == $target_dow){
			$target_time = strtotime("now");
			$start = strtotime($calls[$num-1]['start']);
			$end = strtotime($calls[$num-1]['end']);
			if($target_time >= $start && $target_time <= $end){
				return true;
			}else {
				return false;
			}
		}else {
			return false;
		}
	}
	function sortby_lesson($a,$b) {
    return $a['num-lesson']>$b['num-lesson'];
  }

	function get_lessons($link, $gid){
		$teachers = ($gid == 0) ? array() : get_all_data($link, 'Teachers', '*', 'group-id', $gid);
		$lessons = ($gid == 0) ? array() : get_all_data($link, 'Lessons', '*', 'group-id', $gid);
		for ($i=0; $i < count($lessons); $i++) {
			$teacher = get_data($link, 'Teachers', '*', 'id', $lessons[$i]['teacher-id']);
			$lessons[$i]['teacher'] = $teacher;
			unset($lessons[$i]['teacher-id']);
		}
		return array('lessons' => $lessons, 'teachers' => $teachers);
	}
	function get_self_data($link, $id){
		$default_settings = array(
			'hide-phone' => false,
			'hide-email' => false
		);
		$self = get_data($link, 'Students', '*', 'id', $id);
		$iself = false;
		if ($id == $_SESSION['id']) {
			$iself = true;
		}else {
			array_splice($self, 1, 2);
		}
		$self['settings'] = (is_null($self['settings'])) ? $default_settings : json_decode($self['settings'], true);
		$self['self'] = $iself;
		return $self;
	}
	function get_student_state($state){
		switch ($state) {
			case 'active': return 'Студент активен.'; break;
			case 'waiting-registration': return 'Ожидаем регистрацию.'; break;
			case 'registration-started': return 'Регистрация начата.'; break;
			default: return 'Ошибка.'; break;
		}
	}
	function get_my_group($link, $gid){
		$info = get_data($link, 'Groups', '*', 'id', $gid);
		$teacher = (!is_null($info['teacher-id'])) ? get_data($link, 'Teachers', '*', 'id', $info['teacher-id']) : null;
		$headman = null;
		if(!is_null($info['headman'])){
			$headman = get_data($link, 'Students', '*', 'id', $info['headman']);
			array_splice($headman, 1, 2);
		}
		$students = get_all_data($link, 'Students', '*', 'group-id', $gid, 'id');
		$outstudents = array();
		if (count($students) > 0) {
			$outstudents = array();
			foreach ($students as $key => $student) {
				if($student['id'] != $_SESSION['id']){
					array_push($outstudents, array(
						'id' => $student['id'],
						'name' => $student['name'],
						'phone' => $student['phone'],
						'email' => $student['email'],
						'last-session' => $student['last-session'],
						'status' => $student['status'],
						'gender' => $student['gender'],
						'state' => $student['state']
					));
				}
			}
		}
		$out = array(
			'name' => $info['name'],
			'teacher' => $teacher,
			'headman' => $headman,
			'students' => $outstudents
		);
		return $out;
	}
	function get_initials($name){
		$name = trim($name);
		$spaces = mb_substr_count($name, ' ');

		$out = mb_substr($name, 0, 1);
		if ($spaces) {
			$out .= mb_substr($name, mb_strrpos($name, ' ')+1, 1);

		}
		return $out;
	}
	function is_my_group($link, $gid){
		$tid = get_data($link, 'Groups', 'teacher-id', 'id', $gid);
		if ($tid == $_SESSION['id']) {
			return 1;
		}else {
			return 0;
		}
	}
	function send_in_base($link, $que){
		$result = mysqli_query($link,$que);
		return $result;
	}
	function update_activity($link, $token = null){
		if(is_null($token)){
			send_in_base($link, "UPDATE `Students` SET `last-session`=CURRENT_TIMESTAMP WHERE `id` = ".$_SESSION["id"]);
		}else {
			send_in_base($link, "UPDATE `Students` SET `last-session`=CURRENT_TIMESTAMP WHERE `token` = ".$token);
		}
	}
	function is_admin($link, $id){
		return get_data($link, 'Teachers', 'admin', 'id', $id);
	}
	function get_forced_time($date, $lower=false, $is_birth=false){
		$outdate = strtotime("$date");
		if(date("Y") == date("Y", $outdate)){
			$outdatetime = date("j",$outdate)." ".convert_to_ndate(date("n",$outdate))." ".date("в G:i", $outdate);
			if(date("m") == date("m", $outdate)){
				if(date("d") == date("d", $outdate)){
					$outdatetime = "Сегодня в " . date("G:i", $outdate);
				}else if(date("d")-1 == date("d", $outdate)){
					$outdatetime = "Вчера в " . date("G:i", $outdate);
				}else if(date("d")-2 == date("d", $outdate)){
					$outdatetime = "Позавчера в " . date("G:i", $outdate);
				}else if(date("d")+1 == date("d", $outdate)){
					$outdatetime = "Завтра в " . date("G:i", $outdate);
				}else if(date("d")+2 == date("d", $outdate)){
					$outdatetime = "Послезавтра в " . date("G:i", $outdate);
				}
			}
		}else{
			if($is_birth){
				$date_a = new DateTime($date);
				$date_b = new DateTime();
				$interval = $date_b->diff($date_a);
				$years = $interval->format("%Y");
				$outdatetime = date("j",$outdate)." ".convert_to_ndate(date("n",$outdate))." ".date("Y",$outdate)." г. (".$years.num_to_word($years,array(' год)', ' года)', ' лет)'));
			}else {
				$outdatetime = date("j",$outdate)." ".convert_to_ndate(date("n",$outdate))." ".date("Y",$outdate)." года ".date("в G:i", $outdate);
			}
		}
		if($lower) $outdatetime = mb_strtolower($outdatetime);
		return $outdatetime;
	}

	function get_entry_time($date, $lower=false){
		if (is_null($date)) return null;
		$outdate = strtotime("$date");
		$datetime=new DateTime($date);
		$targetdt=new DateTime("now");
		$diff=$targetdt->diff($datetime);
		if(date("Y") == date("Y", $outdate)){
			$outdatetime = date("j",$outdate)." ".convert_to_ndate(date("n",$outdate))." ".date("в G:i", $outdate);
			if(date("m") == date("m", $outdate)){
				if(date("d") == date("d", $outdate)){
					$outdatetime = "Сегодня в " . date("G:i", $outdate);
					if($diff->format('%h') == 0){
						if($diff->format('%i') >= 5 ){
							$mins = $diff->format('%i');
							$outdatetime = $mins." ".num_to_word($mins,array('минуту', 'минуты', 'минут'))." назад";
						}else {
							$outdatetime = "online";
						}
					}else if($diff->format('%h') > 0 && $diff->format('%h') <= 3){
						$d = $diff->format('%h');
						$outdatetime = $d." ".num_to_word($d,array('час', 'часа', 'часов'))." назад";
					}
				}else if(date("d") - 1 == date("d", $outdate)){
					$outdatetime = "Вчера в " . date("G:i", $outdate);
				}else if(date("d") - 2 == date("d", $outdate)){
					$outdatetime = "Позавчера в " . date("G:i", $outdate);
				}
			}
		}else{
			$outdatetime = date("j",$outdate)." ".convert_to_ndate(date("n",$outdate))." ".date("Y",$outdate)." года ".date("в G:i", $outdate);
		}
		if($lower) $outdatetime = mb_strtolower($outdatetime);
		return $outdatetime;
	}
	function convert_to_ndate($date){
		switch ($date) {
			case 1:
				return "января";
				break;
			case 2:
				return "февраля";
				break;
			case 3:
				return "марта";
				break;
			case 4:
				return "апреля";
				break;
			case 5:
				return "мая";
				break;
			case 6:
				return "июня";
				break;
			case 7:
				return "июля";
				break;
			case 8:
				return "августа";
				break;
			case 9:
				return "сентября";
				break;
			case 10:
				return "октября";
				break;
			case 11:
				return "ноября";
				break;
			case 12:
				return "декабря";
				break;
			default:

				break;
		}
	}
	function convert_day($day){
		switch ($day) {
			case 'monday':case 1: return "Понедельник"; break;
			case 'tuesday':case 2: return "Вторник"; break;
			case 'wednesday':case 3: return "Среда"; break;
			case 'thursday':case 4: return "Четверг"; break;
			case 'friday':case 5: return "Пятница"; break;
			case 'saturday':case 6: return "Суббота"; break;
			case 'sunday':case 7: return "Воскресение"; break;
			default:return "Ошибка!"; break;
		}
	}
	function num_to_word($n, $titles) {
	  $cases = array(2, 0, 1, 1, 1, 2);
	  return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
	}
	function auto_version($file)
	{
	  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
	    return $file;

	  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
	  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
	}
	function RandomToken($length = 32){
  	if(!isset($length) || intval($length) <= 8 ){
			$length = 32;
		}
  	if (function_exists('random_bytes')) {
		return bin2hex(random_bytes($length));
  	}
		if (function_exists('mcrypt_create_iv')) {
			return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
		}
  	if (function_exists('openssl_random_pseudo_bytes')) {
		return bin2hex(openssl_random_pseudo_bytes($length));
  	}
	}
	function validateLoginFields($login, $password){
		$errors = array();
		if (empty($login)){
			$errors[] = array(
          'error_code'=> 1,
          'text'=> 'Давай-ка заполним логин.'
          );
		} elseif(strlen($login) != strlen(str_replace(" ", "", $login))){
      $errors[] = array(
          'error_code'=> 1,
          'text'=> 'Пробелы нельзя ставить. Я сам уберу. 😁'
          );
    } elseif (strpos($login," ") !== false) {
      $errors[] = array(
          'error_code'=> 1,
          'text'=> 'Думаешь сможешь меня обмануть поставив пробелы между букв?) 😂'
          );
    } elseif (strlen($login) < 3) {
      $errors[] = array(
          'error_code'=> 1,
          'text'=> 'Логин должен содержать не менее 3 символов.'
          );
    }elseif (!preg_match('#^[aA-zZ0-9\-_]+$#', $login)) {
      $errors[] = array(
          'error_code'=> 1,
          'text'=> 'В логине присутствуют запрещенные символы.'
          );
    }
    if (empty($password)){
			$errors[] = array(
          'error_code'=> 2,
          'text'=> 'Нельзя забывать про пароль.'
          );
		}elseif(strlen($password) != strlen(str_replace(" ", "", $password))){
      $errors[] = array(
          'error_code'=> 2,
          'text'=> 'Серьёзно? Пробелы в пароле? Ай-яй! 😁'
          );
    }elseif (strpos($password," ") !== false) {
      $errors[] = array(
          'error_code'=> 2,
          'text'=> 'Думаешь сможешь меня обмануть поставив пробелы между букв?) 😂'
          );
    }elseif (strlen($password) < 4) {
      $errors[] = array(
          'error_code'=> 2,
          'text'=> 'Пароль должен содержать не менее 4 символов.'
          );
    }
		return $errors;
	}
?>
