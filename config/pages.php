<?php

return [
    'about-us' => [
        'title' => 'About Us',
        'sections' => [
            [
                'title' => 'Header Banner',
                'fields' => [
                    [
                        'name' => 'banner_title',
                        'label' => 'Banner Title',
                        'type' => 'text',
                        'placeholder' => 'Enter banner title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'banner_description',
                        'label' => 'Banner Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter banner description',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'banner_image',
                        'label' => 'Banner Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ]
                ]
            ],
            [
                'title' => 'Everything Under One Splash',
                'fields' => [
                    [
                        'name' => 'under_one_splash_title',
                        'label' => 'Common Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section common title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'under_one_splash_description',
                        'label' => 'Common Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter section common description',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'under_one_splash_items',
                        'label' => 'Repeating Items',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'title',
                                'label' => 'Item Title',
                                'type' => 'text',
                                'placeholder' => 'Enter item title',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'description',
                                'label' => 'Item Description',
                                'type' => 'textarea',
                                'placeholder' => 'Enter item description',
                                'rules' => ['required', 'string'],
                            ],
                            [
                                'name' => 'image',
                                'label' => 'Item Image',
                                'type' => 'image',
                                'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Mission, Vision & Values',
                'fields' => [
                    [
                        'name' => 'mvv_title',
                        'label' => 'Common Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section common title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'mvv_description',
                        'label' => 'Common Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter section common description',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'mvv_items',
                        'label' => 'Repeating Items',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'title',
                                'label' => 'Item Title',
                                'type' => 'text',
                                'placeholder' => 'Enter item title',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'description',
                                'label' => 'Item Description',
                                'type' => 'textarea',
                                'placeholder' => 'Enter item description',
                                'rules' => ['required', 'string'],
                            ],
                            [
                                'name' => 'image',
                                'label' => 'Item Image',
                                'type' => 'image',
                                'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Dubai\'s Most Exciting Waterpark',
                'fields' => [
                    [
                        'name' => 'waterpark_title',
                        'label' => 'Common Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section common title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'waterpark_description',
                        'label' => 'Common Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter section common description',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'waterpark_items',
                        'label' => 'Repeating Items',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'title',
                                'label' => 'Item Title',
                                'type' => 'text',
                                'placeholder' => 'Enter item title',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'icon',
                                'label' => 'Item Icon / Image',
                                'type' => 'image',
                                'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'birthday-packages' => [
        'title' => 'Birthday Packages',
        'sections' => [
            [
                'title' => 'Header Banner',
                'fields' => [
                    [
                        'name' => 'banner_title',
                        'label' => 'Banner Title',
                        'type' => 'text',
                        'placeholder' => 'Enter banner title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'banner_image',
                        'label' => 'Banner Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ]
                ]
            ],
            [
                'title' => 'Package Heading & Description',
                'fields' => [
                    [
                        'name' => 'heading',
                        'label' => 'Heading Title',
                        'type' => 'text',
                        'placeholder' => 'Enter package page main heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter package description',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ],
            [
                'title' => 'Bottom Section',
                'fields' => [
                    [
                        'name' => 'bottom_title',
                        'label' => 'Bottom Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter bottom section title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'bottom_image',
                        'label' => 'Bottom Section Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ],
                    [
                        'name' => 'bottom_description',
                        'label' => 'Bottom Section Description',
                        'type' => 'wysiwyg',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'balloon-decorations' => [
        'title' => 'Balloon Decorations',
        'sections' => [
            [
                'title' => 'Header Banner',
                'fields' => [
                    [
                        'name' => 'banner_title',
                        'label' => 'Banner Title',
                        'type' => 'text',
                        'placeholder' => 'Enter banner title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'banner_image',
                        'label' => 'Banner Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ]
                ]
            ],
            [
                'title' => 'Decoration Heading & Description',
                'fields' => [
                    [
                        'name' => 'heading',
                        'label' => 'Heading Title',
                        'type' => 'text',
                        'placeholder' => 'Enter page main heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter description',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ],
            [
                'title' => 'Bottom Section',
                'fields' => [
                    [
                        'name' => 'bottom_heading',
                        'label' => 'Bottom Section Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter bottom section heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'bottom_description',
                        'label' => 'Bottom Section Description',
                        'type' => 'wysiwyg',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ],
            [
                'title' => 'Slider Images',
                'description' => 'Add multiple images to show in the page slider',
                'fields' => [
                    [
                        'name' => 'slider_images',
                        'label' => 'Slider Gallery',
                        'type' => 'gallery',
                        'rules' => ['nullable', 'array'],
                    ]
                ]
            ]
        ]
    ]
];
