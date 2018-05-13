<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\BlogPost;
use AppBundle\Exception\TargetNotExistsException;
use AppBundle\Target\Factory;
use AppBundle\Target\TargetInterface;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogPostController.
 */
class BlogPostController extends FOSRestController {
    /**
     * @ApiDoc(
     *     section="Blog Post",
     *     description="Return complete list of blog posts"
     * )
     *
     * @Route(name="api.blog_post.list", path="/blog-post")
     * @Method("GET")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function listPostsAction() {
        $repo = $this->getDoctrine()->getRepository('AppBundle:BlogPost');

        return $this->view($repo->findAll());
    }

    /**
     * @ApiDoc(
     *     section="Blog Post",
     *     description="create blog post"
     * )
     * @Route(name="api.blog_post.create", path="/blog-post/create")
     * @Method("PUT")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function createPostAction() {
        $blogPost = new BlogPost();
        $blogPost->setTitle('');
        $blogPost->setContent('');
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($blogPost);
        $manager->flush();

        return $this->view($blogPost->getId(), 200);
    }

    /**
     * @ApiDoc(
     *     section="Blog Post",
     *     description="update blog post"
     * )
     * @Route(name="api.blog_post.update", path="/blog-post/{blogPost}/update")
     * @Method("POST")
     *
     * @param BlogPost $blogPost
     *
     * @return \FOS\RestBundle\View\View
     */
    public function editPostAction(BlogPost $blogPost, Request $request) {
        if ($newTitle = $request->get('title')) {
            $blogPost->setContent($newTitle);
        }

        if ($newContent = $request->get('content')) {
            $blogPost->setContent($newContent);
        }

        if ($newTags = $request->get('tags')) {
            $tagsArray = explode(',', $newTags);
            $blogPost->setTags($tagsArray);
        }
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($blogPost);
        $manager->flush();

        return $this->view('', 200);
    }

    /**
     * @ApiDoc(
     *     section="Blog Post",
     *     description="Publish post to specified target"
     * )
     * @Route(name="api.blog_post.publish", path="/blog-post/{blogPost}/{target}")
     * @Method("POST")
     *
     * @param BlogPost $blogPost
     * @param $target
     *
     * @return \FOS\RestBundle\View\View
     */
    public function publishPostAction(BlogPost $blogPost, $target) {
        try {
            $target = Factory::factory($target);
        } catch (TargetNotExistsException $e) {
            return $this->view('', 501);
        }

        /* @var TargetInterface $target*/
        $target->publish($blogPost);

        return $this->view('', 200);
    }
}
