<?php
namespace User\V1\Rpc\ResetPasswordNewPassword;

class ResetPasswordNewPasswordControllerFactory
{
    public function __invoke($controllers)
    {
        $newPasswordValidator = $controllers->get('InputFilterManager')
                                        ->get('User\\V1\\Rpc\\ResetPasswordNewPassword\\Validator');
        $resetPasswordService  = $controllers->get(\User\V1\Service\ResetPassword::class);
        return new ResetPasswordNewPasswordController(
            $newPasswordValidator,
            $resetPasswordService
        );
    }
}
