'use strict';

module.exports = function(grunt) {
    // Включить режим разработки?
    var dev_mode = !!grunt.option('dev_mode');
    
    // Параметры компиляции less
    var less_mode = dev_mode ? ':dev' : ':dist';
    
    var less_files = {
        'templates/gigimot/css/stylesheet.css' : 'templates/gigimot/less/stylesheet.less',
        'templates/gigimot/css/product-info.css' : 'templates/gigimot/less/content/product_info.less',
        // 'templates/gigimot/css/compare.css' : 'templates/gigimot/less/content/compare.less'
    };
    
    var grunt_config = {
        pkg: grunt.file.readJSON('package.json'),
        less : {
            dev : {
                files : less_files,
                options : {
                    plugins : [
                        new (require('less-plugin-autoprefix'))({
                            browsers : ['last 2 versions', '> 5%']
                        })
                    ]
                }
            },
            dist : {
                files : less_files,
                options : {
                    plugins : [
                        new (require('less-plugin-autoprefix'))({
                            browsers : ['last 2 versions', '> 5%']
                        }),
                        new (require('less-plugin-clean-css'))({
                            keepSpecialComments : 0,
                            advanced: true,
                            restructuring: true,
                            aggressiveMerging: true,
                            mediaMerging: true
                        })
                    ]
                }
            }
        },
        concat : {
            global: {
                src : [
                    'templates/gigimot/js/sources/libs/3rd_party/jquery-2.2.3.js',
                    'templates/gigimot/js/sources/libs/3rd_party/jquery.mask.js',
                    'templates/gigimot/js/sources/libs/3rd_party/jquery-ui.js',
                    'templates/gigimot/js/sources/libs/3rd_party/perfect-scrollbar.jquery.js',
                    'templates/gigimot/js/sources/libs/3rd_party/slick.js',
                    'templates/gigimot/js/sources/libs/3rd_party/cookies.js',
                    'templates/gigimot/js/sources/libs/eShopmakers/prototype.js',
                    'templates/gigimot/js/sources/libs/eShopmakers/is_mobile_device.js',
                    'templates/gigimot/js/sources/libs/eShopmakers/cart.js',
                    'templates/gigimot/js/sources/global.js'
                ],
                dest : 'templates/gigimot/js/global.js'
            },
            products_listing: {
                src: [
                    'templates/gigimot/js/sources/libs/eShopmakers/filter.js',
                    'templates/gigimot/js/sources/products_listing.js'
                ],
                dest : 'templates/gigimot/js/products-listing.js'
            },
            product_info: {
                src: [
                    'templates/gigimot/js/sources/product_info.js'
                ],
                dest : 'templates/gigimot/js/product-info.js'
            },
            /* compare: {
                src: [
                    'templates/gigimot/js/sources/compare.js'
                ],
                dest : 'templates/gigimot/js/compare.js'
            } */
        },
        watch : {
            less : {
                files : ['templates/gigimot/less/**/*.less'],
                tasks : ['less' + less_mode]
            },
            js : {
                files : ['templates/gigimot/js/sources/**/*.js'],
                tasks : ['concat']
            },
            gruntfile : {
                files : ['Gruntfile.js'],
                options : {
                    reload : true
                },
                tasks : ['less' + less_mode, 'concat']
            }
        }
    };
    
    var default_task = ['less' + less_mode, 'concat'];
    
    // Если не в режиме разработки, то включить обфускацию JS в список заданий
    if(!dev_mode)
    {
        grunt_config.uglify = {
            global : {
                files: {
                    'templates/gigimot/js/global.js' : [
                        'templates/gigimot/js/global.js'
                    ]
                }
            },
            products_listing: {
                files: {
                    'templates/gigimot/js/products-listing.js' : [
                        'templates/gigimot/js/products-listing.js'
                    ]
                }
            },
            product_info: {
                files: {
                    'templates/gigimot/js/product-info.js' : [
                        'templates/gigimot/js/product-info.js'
                    ]
                }
            },
            /* compare: {
                files: {
                    'templates/gigimot/js/compare.js' : [
                        'templates/gigimot/js/compare.js'
                    ]
                }
            } */
        };
        grunt_config.watch.js.tasks.push('uglify');
        grunt_config.watch.gruntfile.tasks.push('uglify');
        default_task= ['less' + less_mode, 'concat', 'uglify'];
    }
    
    if(!grunt.option('skip_watch'))
    {
        default_task.push('watch');
    }
    
    grunt.initConfig(grunt_config);
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.registerTask('default', default_task);
};
