<?php
namespace User\V1\Rpc\ResetPasswordNewPassword;

class ResetPasswordNewPasswordControllerFactory
{
    public function __invoke($controllers)
    {
        $newPasswordValidator = $controllers->get('InputFilterManager')
                                        ->get('User\\V1\\Rpc\\ResetPasswordNewPassword\\Validator');
        $resetPasswordService  = $controllers->get('user.resetpassword');
        return new ResetPasswordNewPasswordController(
            $newPasswordValidator,
            $resetPasswordService
        );
    }
}
