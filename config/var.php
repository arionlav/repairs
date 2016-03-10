<?php

return [
    /**
     * @var string Path to root directory
     */
    'pathToRoot' => '/laravel',

    /**
     * @var int Threshold for admin rules
     */
    'lowestEnterRole' => 4,

    /**
     * @var int Number of articles on single page
     */
    'postsOnPage' => 20,

    /**
     * @var int Number of like pages at footer article
     */
    'countSamePosts' => 7,

    /**
     * @var int Number of popular articles on aside
     */
    'countPopularPosts' => 7,

    /**
     * @var string Word before number of page at pagination (example '/page2/')
     */
    'wordInPagination' => 'page',

    /**
     * @var int Number of max images at creating article
     */
    'maxImagesOnPage' => 15,

    /**
     * @var array Routes, where we must remember url for return after login, etc.
     */
    'rememberUrlOnRoute' => [
        '/',
        'post*/*',
        'category*/*',
        'info',
        'key/*'
    ]
];
