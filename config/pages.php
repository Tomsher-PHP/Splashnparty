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
                        'name' => 'banner_image',
                        'label' => 'Banner Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ],
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
                'title' => 'Dubai\'s Most Exciting Waterpark',
                'fields' => [
                    [
                        'name' => 'waterpark_image',
                        'label' => 'Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ],
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
            ],
            [
                'title' => 'Location Section',
                'fields' => [
                    [
                        'name' => 'location_heading',
                        'label' => 'Heading Title',
                        'type' => 'text',
                        'placeholder' => 'Enter page main heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'location_description',
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter description',
                        'rules' => ['nullable', 'string'],
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
                'title' => 'CTA Banner Section',
                'description' => 'Manage the Call to Action banner background image, title, description, and redirect buttons',
                'fields' => [
                    [
                        'name' => 'cta_bg_image',
                        'label' => 'Background Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ],
                    [
                        'name' => 'cta_title',
                        'label' => 'Banner Title',
                        'type' => 'text',
                        'placeholder' => 'Enter banner title (e.g. Ready to Plan Your Perfect Day?)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'cta_description',
                        'label' => 'Banner Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter banner description...',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'cta_btn1_text',
                        'label' => 'Button 1 Text',
                        'type' => 'text',
                        'placeholder' => 'Enter first button text (e.g. Enquire Now)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'cta_btn1_link',
                        'label' => 'Button 1 Link',
                        'type' => 'text',
                        'placeholder' => 'Enter first button redirect URL',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'cta_btn2_text',
                        'label' => 'Button 2 Text',
                        'type' => 'text',
                        'placeholder' => 'Enter second button text (e.g. Buy Day Passes)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'cta_btn2_link',
                        'label' => 'Button 2 Link',
                        'type' => 'text',
                        'placeholder' => 'Enter second button redirect URL',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ]
                ]
            ],
           
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
                        'name' => 'bottom_images',
                        'label' => 'Bottom Section Images',
                        'type' => 'gallery',
                        'rules' => ['nullable', 'array'],
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
                        'name' => 'bottom_images',
                        'label' => 'Bottom Section Images',
                        'type' => 'gallery',
                        'rules' => ['nullable', 'array'],
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
                        'name' => 'bottom_images',
                        'label' => 'Bottom Section Images',
                        'type' => 'gallery',
                        'rules' => ['nullable', 'array'],
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
    'general-access' => [
        'title' => 'General Access',
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
                        'label' => 'General Access Title',
                        'type' => 'text',
                        'placeholder' => 'Enter general access section heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'birthday_packages_heading',
                        'label' => 'Birthday Packages Title',
                        'type' => 'text',
                        'placeholder' => 'Enter birthday packages section heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ]
                ]
            ],
            [
                'title' => 'Bottom Section',
                'fields' => [
                    [
                        'name' => 'bottom_description',
                        'label' => 'Bottom Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter bottom description details',
                        'rules' => ['nullable', 'string'],
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
            ],
            [
                'title' => 'Map Section',
                'fields' => [
                    [
                        'name' => 'map_title',
                        'label' => 'Map Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter map section title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'map_description',
                        'label' => 'Map Section Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter map section description',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'book-a-trip' => [
        'title' => 'Book a Trip',
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
                'title' => 'Bullet Points Section',
                'fields' => [
                    [
                        'name' => 'bullet_section_title',
                        'label' => 'Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'bullet_points',
                        'label' => 'Bullet Points',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'bullet_text',
                                'label' => 'Bullet Point Text',
                                'type' => 'text',
                                'placeholder' => 'Enter bullet point text',
                                'rules' => ['required', 'string', 'max:500'],
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'privacy-policy' => [
        'title' => 'Privacy Policy',
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
                'title' => 'Policy Content',
                'fields' => [
                    [
                        'name' => 'description',
                        'label' => 'Privacy Policy Description',
                        'type' => 'wysiwyg',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'terms-and-conditions' => [
        'title' => 'Terms & Conditions',
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
                'title' => 'Terms Content',
                'fields' => [
                    [
                        'name' => 'description',
                        'label' => 'Terms & Conditions Description',
                        'type' => 'wysiwyg',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'cancellation-and-refund-policy' => [
        'title' => 'Cancellation & Refund Policy',
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
                'title' => 'Policy Content',
                'fields' => [
                    [
                        'name' => 'description',
                        'label' => 'Cancellation & Refund Policy Description',
                        'type' => 'wysiwyg',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'home' => [
        'title' => 'Home Page',
        'sections' => [
            [
                'title' => 'Main Hero Slider',
                'description' => 'Select multiple active banners to display in the main homepage carousel slider',
                'fields' => [
                    [
                        'name' => 'slider_banners',
                        'label' => 'Homepage Slider Banners',
                        'type' => 'multiselect',
                        'options_source' => 'banners',
                        'rules' => ['nullable', 'array'],
                    ]
                ]
            ],
            [
                'title' => 'Featured Services Section',
                'description' => 'Manage the featured cards/sections displayed on the homepage below the hero slider (e.g. Waterpark Day Pass, Birthday Parties, etc.)',
                'fields' => [
                    [
                        'name' => 'featured_services',
                        'label' => 'Featured Services / Categories',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'image',
                                'label' => 'Card Image',
                                'type' => 'image',
                                'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                            ],
                            [
                                'name' => 'title',
                                'label' => 'Title',
                                'type' => 'text',
                                'placeholder' => 'Enter title (e.g. Waterpark Day Pass)',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'subtitle',
                                'label' => 'Subtitle',
                                'type' => 'text',
                                'placeholder' => 'Enter subtitle (e.g. Splash & Play All Day)',
                                'rules' => ['nullable', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'link',
                                'label' => 'Link / URL',
                                'type' => 'text',
                                'placeholder' => 'Enter redirect URL (e.g. /general-access)',
                                'rules' => ['nullable', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'badge',
                                'label' => 'Badge Text (Optional)',
                                'type' => 'text',
                                'placeholder' => 'Enter custom badge text (e.g. Most Popular)',
                                'rules' => ['nullable', 'string', 'max:50'],
                            ],
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Welcome Section',
                'description' => 'Manage the welcome text and introductory button on the homepage (e.g. Welcome to Splash \'n\' Party)',
                'fields' => [
                    [
                        'name' => 'welcome_title',
                        'label' => 'Title',
                        'type' => 'text',
                        'placeholder' => 'Enter title (e.g. Welcome to Splash \'n\' Party)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'welcome_description',
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter introductory description...',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'welcome_btn_text',
                        'label' => 'Button Text',
                        'type' => 'text',
                        'placeholder' => 'Enter button text (e.g. Learn More About Our Facility)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'welcome_btn_link',
                        'label' => 'Button Link',
                        'type' => 'text',
                        'placeholder' => 'Enter redirect URL (e.g. /about-us)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ]
                ]
            ],
            [
                'title' => 'Location Section',
                'description' => 'Manage the section title and description for the location area on the homepage',
                'fields' => [
                    [
                        'name' => 'location_title',
                        'label' => 'Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section title (e.g. Our Locations)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'location_description',
                        'label' => 'Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter section description...',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ],
            [
                'title' => 'Discover Section (General)',
                'description' => 'Manage the section title and subtitle for the Discover categories area',
                'fields' => [
                    [
                        'name' => 'discover_title',
                        'label' => 'Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section title (e.g. Discover Splash \'n\' Party)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'discover_subtitle',
                        'label' => 'Section Subtitle',
                        'type' => 'text',
                        'placeholder' => 'Enter section subtitle (e.g. Ready to experience the magic? Let the adventure begin!)',
                        'rules' => ['nullable', 'string', 'max:500'],
                    ]
                ]
            ],
            [
                'title' => 'Discover Categories Section',
                'description' => 'Dynamically add category tabs and their respective dynamic slide/image carousels',
                'fields' => [
                    [
                        'name' => 'discover_categories',
                        'label' => 'Discover Categories',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'name',
                                'label' => 'Category Name',
                                'type' => 'text',
                                'placeholder' => 'Enter category (e.g. Popular, Slide, Zipline)',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'icon',
                                'label' => 'Category Icon',
                                'type' => 'image',
                                'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                            ],
                            [
                                'name' => 'slides',
                                'label' => 'Slides / Images under Category',
                                'type' => 'repeater',
                                'fields' => [
                                    [
                                        'name' => 'image',
                                        'label' => 'Slide Image',
                                        'type' => 'image',
                                        'rules' => ['required', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                                    ],
                                    [
                                        'name' => 'title',
                                        'label' => 'Slide Title',
                                        'type' => 'text',
                                        'placeholder' => 'Enter title (e.g. Mini Slides)',
                                        'rules' => ['required', 'string', 'max:255'],
                                    ],
                                    [
                                        'name' => 'description',
                                        'label' => 'Slide Subtitle / Description',
                                        'type' => 'text',
                                        'placeholder' => 'Enter subtitle (e.g. Perfect For Little Splashers)',
                                        'rules' => ['nullable', 'string', 'max:255'],
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Bakery & Café Section',
                'description' => 'Manage the side-by-side bakery and café cards displayed on the homepage',
                'fields' => [
                    [
                        'name' => 'bakery_cafe_cards',
                        'label' => 'Bakery & Café Cards',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'image',
                                'label' => 'Card Image',
                                'type' => 'image',
                                'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                            ],
                            [
                                'name' => 'title',
                                'label' => 'Title',
                                'type' => 'text',
                                'placeholder' => 'Enter title (e.g. Sweet Creations by Sprinkles Bakery)',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'description',
                                'label' => 'Description',
                                'type' => 'textarea',
                                'placeholder' => 'Enter description...',
                                'rules' => ['nullable', 'string'],
                            ],
                            [
                                'name' => 'btn_text',
                                'label' => 'Button Text',
                                'type' => 'text',
                                'placeholder' => 'Enter button text (e.g. Explore Bakery & Add-Ons)',
                                'rules' => ['nullable', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'btn_link',
                                'label' => 'Button Link',
                                'type' => 'text',
                                'placeholder' => 'Enter redirect URL (e.g. /cake-listing)',
                                'rules' => ['nullable', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'features',
                                'label' => 'Features / Bullet Points',
                                'type' => 'repeater',
                                'fields' => [
                                    [
                                        'name' => 'text',
                                        'label' => 'Feature Bullet Point',
                                        'type' => 'text',
                                        'placeholder' => 'Enter bullet point (e.g. Personal Party Coordinator)',
                                        'rules' => ['required', 'string', 'max:255'],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Latest Offers & Camps Section',
                'description' => 'Manage the section title, description, and list of camp/offer promotion images',
                'fields' => [
                    [
                        'name' => 'offers_camps_title',
                        'label' => 'Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section title (e.g. Latest Offers & Camps)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'offers_camps_description',
                        'label' => 'Section Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter section description...',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'offers_camps_images',
                        'label' => 'Camp & Offer Images',
                        'type' => 'gallery',
                        'rules' => ['nullable', 'array'],
                    ]
                ]
            ],
            [
                'title' => 'Testimonials Section',
                'description' => 'Manage the section title and description',
                'fields' => [
                    [
                        'name' => 'testimonials_title',
                        'label' => 'Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section title (e.g. What Our Guests Say)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'testimonials_description',
                        'label' => 'Section Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter section description...',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ],
            [
                'title' => 'CTA Banner Section',
                'description' => 'Manage the Call to Action banner background image, title, description, and redirect buttons',
                'fields' => [
                    [
                        'name' => 'cta_bg_image',
                        'label' => 'Background Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ],
                    [
                        'name' => 'cta_title',
                        'label' => 'Banner Title',
                        'type' => 'text',
                        'placeholder' => 'Enter banner title (e.g. Ready to Plan Your Perfect Day?)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'cta_description',
                        'label' => 'Banner Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter banner description...',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'cta_btn1_text',
                        'label' => 'Button 1 Text',
                        'type' => 'text',
                        'placeholder' => 'Enter first button text (e.g. Enquire Now)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'cta_btn1_link',
                        'label' => 'Button 1 Link',
                        'type' => 'text',
                        'placeholder' => 'Enter first button redirect URL',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'cta_btn2_text',
                        'label' => 'Button 2 Text',
                        'type' => 'text',
                        'placeholder' => 'Enter second button text (e.g. Buy Day Passes)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'cta_btn2_link',
                        'label' => 'Button 2 Link',
                        'type' => 'text',
                        'placeholder' => 'Enter second button redirect URL',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ]
                ]
            ],
            [
                'title' => 'Our Partners Section',
                'description' => 'Manage the section title',
                'fields' => [
                    [
                        'name' => 'partners_title',
                        'label' => 'Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section title (e.g. Our Partners)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ]
                ]
            ],
            [
                'title' => 'Instagram Section',
                'description' => 'Manage the Instagram section title and description',
                'fields' => [
                    [
                        'name' => 'instagram_title',
                        'label' => 'Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter section title (e.g. Follow Us On Instagram)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'instagram_description',
                        'label' => 'Section Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter section description...',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'instagram_code',
                        'label' => 'Elfsight Instagram Feed Code',
                        'type' => 'textarea',
                        'placeholder' => 'Paste your Elfsight Instagram feed embed code here...',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'waterpark' => [
        'title' => 'Waterpark Page',
        'sections' => [
            [
                'title' => 'Header Banner Section',
                'description' => 'Manage the top hero banner details',
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
                'title' => 'Main Introduction',
                'description' => 'Manage the main heading and description of the Waterpark page',
                'fields' => [
                    [
                        'name' => 'heading',
                        'label' => 'Main Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter main heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'description',
                        'label' => 'Main Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter main description',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ],
            [
                'title' => 'Locations & Categories Overview',
                'description' => 'Manage section headings and descriptions for Attractions, Adventures, and Locations',
                'fields' => [
                    [
                        'name' => 'locations_title',
                        'label' => 'Locations Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter locations title (e.g. Our Branches & Locations)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'attractions_title',
                        'label' => 'Attractions Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter attractions title (e.g. High-Splash Attractions)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'attractions_description',
                        'label' => 'Attractions Section Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter attractions description',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'adventures_title',
                        'label' => 'Adventures Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter adventures title (e.g. Extreme Water Adventures)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'adventures_description',
                        'label' => 'Adventures Section Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter adventures description',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ],
            [
                'title' => 'Pricing Details Section',
                'description' => 'Manage the admission rate and pricing summary texts',
                'fields' => [
                    [
                        'name' => 'pricing_title',
                        'label' => 'Pricing Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter pricing title (e.g. Admission Rates & Passes)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'pricing_description',
                        'label' => 'Pricing Section Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter pricing description',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'price_heading',
                        'label' => 'Pricing Highlight Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter pricing highlight heading (e.g. Special Offers)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ]
                ]
            ],
            [
                'title' => 'FAQ Section',
                'description' => 'Manage the FAQ section title and repeating list of questions & answers',
                'fields' => [
                    [
                        'name' => 'faq_title',
                        'label' => 'FAQ Section Title',
                        'type' => 'text',
                        'placeholder' => 'Enter FAQ section title (e.g. Frequently Asked Questions)',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'faqs',
                        'label' => 'Frequently Asked Questions',
                        'type' => 'repeater',
                        'fields' => [
                            [
                                'name' => 'question',
                                'label' => 'Question',
                                'type' => 'text',
                                'placeholder' => 'Enter question',
                                'col' => 'col-12',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'answer',
                                'label' => 'Answer',
                                'type' => 'textarea',
                                'placeholder' => 'Enter answer',
                                'rows' => 2,
                                'rules' => ['required', 'string'],
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Bottom Banner (CTA)',
                'description' => 'Manage the call-to-action bottom banner content and redirection buttons',
                'fields' => [
                    [
                        'name' => 'bottom_banner_image',
                        'label' => 'Bottom Banner Image',
                        'type' => 'image',
                        'rules' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:4096'],
                    ],
                    [
                        'name' => 'bottom_banner_heading',
                        'label' => 'Bottom Banner Heading',
                        'type' => 'text',
                        'placeholder' => 'Enter bottom banner heading',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'bottom_banner_description',
                        'label' => 'Bottom Banner Description',
                        'type' => 'textarea',
                        'placeholder' => 'Enter bottom banner description',
                        'rules' => ['nullable', 'string'],
                    ],
                    [
                        'name' => 'bottom_banner_btn_title_1',
                        'label' => 'Button 1 Title',
                        'type' => 'text',
                        'placeholder' => 'Enter first button title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'bottom_banner_btn_link_1',
                        'label' => 'Button 1 Link',
                        'type' => 'text',
                        'placeholder' => 'Enter first button redirect link',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'bottom_banner_btn_title_2',
                        'label' => 'Button 2 Title',
                        'type' => 'text',
                        'placeholder' => 'Enter second button title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'bottom_banner_btn_link_2',
                        'label' => 'Button 2 Link',
                        'type' => 'text',
                        'placeholder' => 'Enter second button redirect link',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ]
                ]
            ]
        ]
    ],
    'footer' => [
        'title' => 'Footer Settings',
        'sections' => [
            [
                'title' => 'Footer Menus',
                'description' => 'Configure footer menus (maximum 3 repeatable sections, each with up to 6 links)',
                'fields' => [
                    [
                        'name' => 'menu_sections',
                        'label' => 'Menu Sections',
                        'type' => 'repeater',
                        'rules' => ['nullable', 'array', 'max:3'],
                        'fields' => [
                            [
                                'name' => 'main_title',
                                'label' => 'Main Title',
                                'type' => 'text',
                                'placeholder' => 'e.g. Quick Links',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'links',
                                'label' => 'Links List',
                                'type' => 'repeater',
                                'rules' => ['nullable', 'array', 'max:6'],
                                'fields' => [
                                    [
                                        'name' => 'link_name',
                                        'label' => 'Link Name',
                                        'type' => 'text',
                                        'placeholder' => 'e.g. About Us',
                                        'rules' => ['required', 'string', 'max:255'],
                                    ],
                                    [
                                        'name' => 'link_url',
                                        'label' => 'Link / URL',
                                        'type' => 'text',
                                        'placeholder' => 'e.g. /about-us',
                                        'rules' => ['required', 'string', 'max:255'],
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Social Media Info',
                'description' => 'Configure social media title and repeatable networks list with SVG icons',
                'fields' => [
                    [
                        'name' => 'social_title',
                        'label' => 'Social Media Main Title',
                        'type' => 'text',
                        'placeholder' => 'e.g. Follow Us',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'social_links',
                        'label' => 'Social Media Links',
                        'type' => 'repeater',
                        'rules' => ['nullable', 'array'],
                        'fields' => [
                            [
                                'name' => 'name',
                                'label' => 'Social Media Name',
                                'type' => 'text',
                                'placeholder' => 'e.g. Facebook',
                                'rules' => ['required', 'string', 'max:255'],
                            ],
                            [
                                'name' => 'svg_code',
                                'label' => 'SVG Code',
                                'type' => 'textarea',
                                'placeholder' => 'Paste raw SVG code here',
                                'description' => 'For best display results, choose/adjust the SVG width and height to 22 (e.g. width="22" height="22").',
                                'rules' => ['nullable', 'string'],
                                'rows' => 4,
                            ],
                            [
                                'name' => 'link',
                                'label' => 'Social Media Link',
                                'type' => 'text',
                                'placeholder' => 'https://facebook.com/...',
                                'rules' => ['required', 'url', 'max:255'],
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'news-updates' => [
        'title' => 'News & Updates',
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
                'title' => 'News & Updates Heading & Description',
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
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ],
    'news-updates-details' => [
        'title' => 'News & Updates Details',
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
                'title' => 'Details Section',
                'fields' => [
                    [
                        'name' => 'related_content_title',
                        'label' => 'Related Content Title',
                        'type' => 'text',
                        'placeholder' => 'Enter related content title',
                        'rules' => ['nullable', 'string', 'max:255'],
                    ],
                    [
                        'name' => 'description',
                        'label' => 'Description',
                        'type' => 'textarea',
                        'rules' => ['nullable', 'string'],
                    ]
                ]
            ]
        ]
    ]
];

