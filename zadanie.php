<?

public function updateUsers($users)
{
	foreach ($users as $user) {
		try {
			if ($user['name'] && $user['login'] && $user['email'] && $user['password'] && strlen($user['name']) >= 10)
                preg_match('/^(?=[^a-z\n]*[a-z])(?=[^A-Z\n]*[A-Z])(?=[^\d\n]*\d)(?=[^!@?\n]*[!@?]).{10,}$/', $user['password'], $matches);
                if ($matches)
                    DB::table('users')->where('id', $user['id'])->update([
                        'name' => $user['name'],
                        'login' => $user['login'],
                        'email' => $user['email'],
                        'password' => Hash::make($user['password'])
                    ]);
		} catch (\Throwable $e) {
			return Redirect::back()->withErrors(['error', ['We couldn\'t update user: ' . $e->getMessage()]]);
		}
	}
	return Redirect::back()->with(['success', 'All users updated.']);
}

public function storeUsers($users)
{
    foreach ($users as $user) {
        try {
			if ($user['name'] && $user['login'] && $user['email'] && $user['password'] && strlen($user['name']) >= 10)
                preg_match('/^(?=[^a-z\n]*[a-z])(?=[^A-Z\n]*[A-Z])(?=[^\d\n]*\d)(?=[^!@?\n]*[!@?]).{10,}$/', $user['password'], $matches);
                if ($matches)
                    DB::table('users')->insert([
                        'name' => $user['name'],
                        'login' => $user['login'],
                        'email' => $user['email'],
                        'password' =>Hash::make($user['password'])
                    ]);
                    $this->sendEmail($user);
        } catch (\Throwable $e) {
            return Redirect::back()->withErrors(['error', ['We couldn\'t store user: ' . $e->getMessage()]]);
        }
    }
    return Redirect::back()->with(['success', 'All users created.']);
}

private function sendEmail($user)
{
    $message = 'Account has beed created. You can log in as <b>' . $user['login'] . '</b>';
        Mail::to($user['email'])
            ->cc('support@company.com')
            ->subject('New account created')
            ->queue($message);
}

?>
