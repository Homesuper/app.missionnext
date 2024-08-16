<?php
namespace MissionNext\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use MissionNext\Api\Exceptions\AuthenticationException;
use MissionNext\Models\Language\LanguageModel;
use MissionNext\Models\User\User;

class Listener
{
    /** @var  Request */
    protected $request;

    /** @var Manager */
    protected $authManager;

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @param Manager $authManager
     */
    public function __construct(Manager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Handle authentication
     */
    public function handle()
    {
        $request = $this->request;

        (!$request->headers->has('X-Auth')
            || !$request->headers->has('X-Auth-Hash')
            || !$request->headers->has('X-User')
            || !$request->headers->has('X-Lang')
            || !$request->query->get('timestamp')
        ) && App::abort(400, "Bad Request");

        $token = new Token();
        $token->uri = $request->server->get("REQUEST_URI");
        $token->publicKey = $request->headers->get('X-Auth');
        $token->hash = $request->headers->get('X-Auth-Hash');
        $token->created = (int)$request->query->get('timestamp');
        $token->currentUser = User::find($request->headers->get('X-User'));
        $token->language = LanguageModel::find($request->headers->get('X-Lang'));
        $this->authManager->authenticate($token);
    }
} 