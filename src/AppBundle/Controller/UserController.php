<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use AppBundle\Form\User\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserController extends Controller
{

    public function loginAction(Request $request)
    {
        $User = new User();
        $loginForm = $this->createForm(LoginType::class, $User);

        $loginForm->handleRequest($request);

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            $data = $loginForm->getData();

            $userRepository = $this->getDoctrine()->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $data->getEmail()]);

            if ($user == null) {
                if (!$this->createUser($User, $data)) {
                    $this->addFlash('error', 'Wystąpił błąd podczas tworzenie użytkownika!');

                    return $this->redirectToRoute('user_login');
                }
            }

            if (!$this->manualLogin($data, $request)) {
                $this->addFlash('error', 'Wystąpił błąd podczas logowania!');

                return $this->redirectToRoute('user_login');
            }



            return $this->redirectToRoute('page_home');
        }

        return $this->render('full/User/login.html.twig', [
            'loginForm' => $loginForm->createView()
        ]);
    }

    /**
     * @param User $User
     * @param $userData
     *
     * @return bool
     */
    protected function createUser(User $User, $userData)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $User->setEmail($userData->getEmail());
            $User->setUsername($userData->getUsername());

            $em->persist($User);
            $em->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param $userData
     * @param Request $request
     *
     * @return bool
     */
    protected function manualLogin($userData, Request $request)
    {
        /** @var UserRepository $repo */
        $repo = $this->getDoctrine()->getRepository(User::class);

        $user = $repo->loadUserByUsernameAndEmail($userData->getEmail(), $userData->getUsername());

        if ($user instanceof User) {
            try {
                $token = new UsernamePasswordToken($user, null, "page_security", $user->getRoles());
                $this->get('security.token_storage')->setToken($token);

                $event = new InteractiveLoginEvent($request, $token);
                $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            }catch (\Exception $e){
                return false;
            }

            return true;
        }

        return false;
    }
}
