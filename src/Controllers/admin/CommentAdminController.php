<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;
use App\Data\Managers\CommentManager;
use App\Request;
use App\Response;
use App\Session;

class CommentAdminController extends AdminController
{
    private CommentManager $commentManager;

    public function __construct()
    {
        $this->commentManager = new CommentManager();
    }

    public function index(Request $request, Response $response)
    {
        unset($_SESSION['flash_messages']);
        if($this->isAdmin($response))
        {
            $comments = $this->commentManager->findAll();
            echo $this->render('/admin/comments.html.twig', compact('comments'));
        }
        else{
            Session::setFlash('error', "Vous n'etes pas autorisés à acceder a cette page");
            $response->redirect('/');
        }
    }

    public function approuveComment(int $id, Request $request, Response $response)
    {
        if($this->isAdmin($response))
        {
            $comment = $this->commentManager->find($id);
            $comment->setIsApprouved($comment->getIsApprouved() ? 0 : 1);
            $this->commentManager->update($comment);
//            Session::setFlash('success', "Vous n'etes pas autorisés à acceder a cette page");
            $response->redirect('/secadmin/comments');
        }
        else{
            Session::setFlash('error', "Vous n'etes pas autorisés à acceder a cette page");
            $response->redirect('/');
        }
    }
}