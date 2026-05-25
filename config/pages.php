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
    ],
    'party-extras' => [
        'title' => 'Party Extras',
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
                'title' => 'Extras Heading & Description',
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
    'kids-meal' => [
        'title' => 'Kids Meal',
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
                'title' => 'Kids Meal Heading & Description',
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
    'adult-platters' => [
        'title' => 'Adult Platters',
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
                'title' => 'Adult Platters Heading & Description',
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
            ]
        ]
    ],
    'rental-services' => [
        'title' => 'Rental Services',
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
                'title' => 'Rental Services Heading & Description',
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
                        'name' => 'bottom_title',
                        'label' => 'Bottom Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter bottom section title',
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
                'title' => 'Services Repeater Section',
                'fields' => [
                    [
                        'name' => 'services_items',
                        'label' => 'Services Repeater Items',
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
            ]
        ]
    ],
    'outdoor-events' => [
        'title' => 'Outdoor Events',
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
                'title' => 'Outdoor Events Heading & Description',
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
                'title' => 'Events Content Section',
                'fields' => [
                    [
                        'name' => 'image',
                        'label' => 'Section Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ],
                    [
                        'name' => 'body_content',
                        'label' => 'Content',
                        'type' => 'wysiwyg',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'cake-listing' => [
        'title' => 'Cake Listing',
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
                'title' => 'Cake Listing Heading & Description',
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
    'cafe-menu' => [
        'title' => 'Cafe Menu',
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
                'title' => 'Cafe Menu Heading & Description',
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
            ]
        ]
    ],
    'admission-rates' => [
        'title' => 'Admission Rates',
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
                'title' => 'Heading & Description',
                'fields' => [
                    [
                        'name' => 'heading',
                        'label' => 'Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter page main heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter page description',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ],
            [
                'title' => 'Section Headings',
                'fields' => [
                    [
                        'name' => 'general_access_heading',
                        'label' => 'General Access Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter general access section heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'birthday_packages_heading',
                        'label' => 'Birthday Packages Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter birthday packages section heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ]
                ]
            ]
        ]
    ],
    'faqs' => [
        'title' => 'FAQs',
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
            ]
        ]
    ],
    'image-gallery' => [
        'title' => 'Image Gallery',
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
                'title' => 'Gallery Heading & Description',
                'fields' => [
                    [
                        'name' => 'heading',
                        'label' => 'Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter main heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'wysiwyg',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'video-gallery' => [
        'title' => 'Video Gallery',
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
                'title' => 'Gallery Heading & Description',
                'fields' => [
                    [
                        'name' => 'heading',
                        'label' => 'Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter main heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'wysiwyg',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'contact-us' => [
        'title' => 'Contact Us',
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
                'title' => 'Heading & Description',
                'fields' => [
                    [
                        'name' => 'heading',
                        'label' => 'Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter main heading',
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
                'title' => 'Form Section',
                'fields' => [
                    [
                        'name' => 'form_section_title',
                        'label' => 'Form Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter form section title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'form_section_description',
                        'label' => 'Form Section Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter form section description',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'form_section_image',
                        'label' => 'Form Section Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ]
                ]
            ],
            [
                'title' => 'Form Categories',
                'fields' => [
                    [
                        'name' => 'form_categories',
                        'label' => 'Form Categories',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'title',
                                'label' => 'Category Title',
                                'type' => 'text',
                                'placeholder' => 'Enter category title',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'icon',
                                'label' => 'Category Icon',
                                'type' => 'image',
                                'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
