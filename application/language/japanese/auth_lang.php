<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Author: Daniel Davis
*         @ourmaninjapan
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.09.2013
*
* Description:  English language file for Ion Auth example views
*
*/



// Login
$lang['username']                                       = 'ユーザー名';
$lang['password']                                       = 'パスワード';
$lang['mailaddress']                                    = 'メールアドレス';
$lang['timezone']                                       = 'タイムゾーン +9（日本）';
$lang['remember_me']                                    = 'ユーザー名とパスワードを記憶';
$lang['login']                                          = 'ログイン';
$lang['forgot_password']                                = 'パスワードを忘れた場合';
$lang['register']                                       = '新規ユーザー登録';
$lang['authentication']                                 = '認証コード';
$lang['send']                                           = 'メール送信';
//Register
$lang['register_title']                                 = '新規ユーザー登録';

$lang['confirm_pass']                                   = 'パスワードの再入力';
$lang['New user registration']                          = '新規ユーザー登録';

$lang['confirm_pass']                                   = 'パスワードの再入力';
$lang['agree_button']                                   ='同意して登録';


// Change Password
$lang['incorrect_password_email']                              = 'メールアドレスまたはパスワードが登録と一致しません。';
$lang['incorrect_authontication_code']                         = '認証コード入力が正しくありません。';
$lang['token_invalid']                                         = 'トークンが無効です。';
$lang['payload_invalid']                                       = 'ペイロードが無効です。';
$lang['token_expired']                                         = 'トークンの有効期限が切れています。';
$lang['whoops']                                                = 'おっとっと！';
$lang['mail_failed']                                           = 'メール送信に失敗しました。';
$lang['termsofservice']                                        = '利用規約に同意してください。';
$lang['change_password_validation_new_password_confirm_label'] = 'Confirm New Password';

// Forgot Password
$lang['forgot_password_heading']                               = 'パスワードのリセットリクエスト';


// Reset Password
$lang['reset_password_heading']                               = 'Change Password';
$lang['reset_password_new_password_label']                    = 'New Password (at least %s characters long):';
$lang['reset_password_new_password_confirm_label']            = 'Confirm New Password:';
$lang['reset_password_submit_btn']                            = 'Change';
$lang['reset_password_validation_new_password_label']         = 'New Password';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirm New Password';


//form validation

$lang['required'] 			= "%s 欄は必須です。";
$lang['isset']				= "%s 欄は空欄にできません。";
$lang['valid_email']		= "%s 欄には正しいEmailアドレスを入力する必要があります。";
$lang['valid_emails'] 		= "%s 欄には正しいEmailアドレスを入力する必要があります。";
$lang['valid_url'] 			= "%s 欄には正しいURLを入力する必要があります。";
$lang['valid_ip'] 			= "%s 欄には正しいIPアドレスを入力する必要があります。";
$lang['min_length']			= "%s 欄は最低 %s 文字以上でなければなりません。";
$lang['max_length']			= "%s 欄は %s 文字を超えてはいけません。";
$lang['exact_length']		= "%s 欄は %s 文字でなければなりません。";
$lang['alpha']				= "%s 欄には、半角アルファベット以外は入力できません。";
$lang['alpha_numeric']		= "%s 欄には、半角英数字以外は入力できません。";
$lang['alpha_dash']			= "%s 欄には、半角英数字、アンダースコア(_)、ハイフン(-)以外は入力できません。";
$lang['numeric']			= "%s 欄には、数字以外は入力できません。";
$lang['is_numeric']			= "%s 欄には、数値以外は入力できません。";
$lang['integer']			= "%s 欄には、整数以外は入力できません。";
$lang['regex_match']		= "%s 欄は、正しい形式ではありません。";
$lang['matches']			= "%s 欄が %s 欄と一致しません。";
$lang['is_unique'] 			= "%s 欄には、他で使われている値は入力できません。";
$lang['is_natural']			= "%s 欄には、正の整数以外は入力できません。";
$lang['is_natural_no_zero']	= "%s 欄には、0より大きい整数以外は入力できません。";
$lang['decimal']			= "%s 欄は10進数しか入力できません。";
$lang['less_than']			= "%s 欄は %s より小さい数しか入力できません。";
$lang['greater_than']		= "%s 欄は %s より大きな数しか入力できません。";

//
$lang['authentication_code']                                  = '認証コード';
$lang['user_name']                                            = 'ユーザー名';
$lang['mailaddress']                                          = 'メールアドレス';
$lang['password']                                             = 'パスワード';
$lang['confirm_password']                                     = 'パスワードの再入力';
$lang['invalid_code']                                         = '認証コード入力が正しくありません。';
