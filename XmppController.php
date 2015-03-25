<?php

/**
 * Created by PhpStorm.
 * User: a
 * Date: 14-6-24
 * Time: 下午2:45
 */
class XmppController extends BaseController
{
    /**
     *
     * xmpp  前置绑定
     */
    public function actionXmppBind()
    {

        $json = new JsonMessage();

        Yii::import('ext.xmppRebind.XmppPrebind');
        if ($this->isLogin()) {
            try {
                $pos_UserRegisterService = new pos_UserRegisterService();
                $UserXmppInfo = $pos_UserRegisterService->getXmppPasswd(user()->id);
                if ($UserXmppInfo) {
                    $xmppPrebind = new XmppPrebind(Yii::app()->params['xmpp_host'], Yii::app()->params['xmpp_Uri'], 'web');
                    $xmppPrebind->connect($UserXmppInfo['id'], $UserXmppInfo['pw']);
                    $xmppPrebind->auth();
                    $sessionInfo = $xmppPrebind->getSessionInfo();
                    Yii::app()->request->cookies['jid'] = new  CHttpCookie('jid', $sessionInfo['jid']);
                    Yii::app()->request->cookies['sid'] = new  CHttpCookie('sid', $sessionInfo['sid']);
                    Yii::app()->request->cookies['rid'] = new  CHttpCookie('rid', $sessionInfo['rid']);
                    $json->success = true;

                }

            } catch (Exception $e) {

            }

        }
        $this->renderJSON($json);

    }
} 