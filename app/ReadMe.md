Untuk mengubah redirect setelah login / register : 'vendor\laravel\ui\auth-backend\RedirectsUsers.php'
    public function redirectPath()
    {
        // if (method_exists($this, 'redirectTo')) {
        //     return $this->redirectTo();
        // }

        // return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
        return '/role';
    }
