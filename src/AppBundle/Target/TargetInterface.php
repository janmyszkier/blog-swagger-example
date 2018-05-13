<?php

namespace AppBundle\Target;

use AppBundle\Entity\BlogPost;

interface TargetInterface {
    public function publish(BlogPost $blogPost);
}
