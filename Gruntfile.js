module.exports = function (grunt) {
    'use strict';

    // Load tasks.
    require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});

    // Force use of Unix newlines
    grunt.util.linefeed = '\n';

    // Set default encoding
    grunt.file.defaultEncoding = 'utf8';

    // Project configuration.
    grunt.initConfig({

        // Metadata.
        pkg: grunt.file.readJSON('package.json'),

        wpthemedata: '/*\n' +
            'Theme Name:  Pillana(r)t\n' +
            'Description: <%= pkg.description %>\n' +
            'Version:     <%= pkg.version %>\n' +
            'Author:      <%= pkg.author.name %>\n' +
            'Text Domain: pillanart-theme\n' +
            '*/',

        builddata: 'v<%= pkg.version %> by <%= pkg.author.name %> | ' +
            '<%= grunt.template.today("mmm d \'yy") %> ' +
            'at <%= grunt.template.today("H:MM") %> */',

        // Task configuration.
        less: {
            theme: {
                options: {
                    strictMath: true
                },
                src: 'src/theme/less/pillanart.less',
                dest: 'web/public/assets/css/<%= pkg.name %>.css'
            },
            editor: {
                src: 'src/theme/less/editor/editor.less',
                dest: 'web/public/app/themes/pillanart/editor-style.css'
            },
            members: {
                src: 'src/plugin/less/pillanart-ms.less',
                dest: 'web/public/app/plugins/pillanart-ms/assets/pillanart-ms.css'
            },
            plugin: {
                src: 'src/plugin/less/thumbnail-col.less',
                dest: 'web/public/app/plugins/site-functionality/assets/thumbnail-col.css'
            }
        },

        autoprefixer: {
            options: {
                browsers: '> 5%, last 2 version'
            },
            theme: {
                src: '<%= less.theme.dest %>'
            },
            editor: {
                src: '<%= less.editor.dest %>'
            },
            members: {
                src: '<%= less.members.dest %>'
            },
            plugin: {
                src: '<%= less.plugin.dest %>'
            }
        },

        csscomb: {
            options: {
                config: 'src/.csscomb.json'
            },
            theme: {
                files: {'<%= less.theme.dest %>': ['<%= less.theme.dest %>']}
            },
            editor: {
                files: {'<%= less.editor.dest %>': ['<%= less.editor.dest %>']}
            },
            members: {
                files: {'<%= less.members.dest %>': ['<%= less.members.dest %>']}
            },
            plugin: {
                files: {'<%= less.plugin.dest %>': ['<%= less.plugin.dest %>']}
            }
        },

        cmq: {
            options: {
                log: false
            },
            theme: {
                files: {'<%= less.theme.dest %>': ['<%= less.theme.dest %>']}
            }
        },

        cssmin: {
            options: {
                // compatibility: 'ie8',
                keepSpecialComments: 1,
                advanced: false
            },
            theme: {
                src: '<%= less.theme.dest %>',
                dest: 'web/public/assets/css/<%= pkg.name %>.min.css'
            },
            editor: {
                src: '<%= less.editor.dest %>',
                dest: '<%= less.editor.dest %>'
            },
            members: {
                src: '<%= less.members.dest %>',
                dest: 'web/public/app/plugins/pillanart-ms/assets/pillanart-ms.min.css'
            },
            plugin: {
                src: '<%= less.plugin.dest %>',
                dest: 'web/public/app/plugins/site-functionality/assets/thumbnail-col.min.css'
            }
        },

        concat: {
            options: {
                stripBanners: false
            },
            target: {
                src: [
                    "src/theme/js/jquery.easing.1.4.js",
                    "src/theme/js/jquery.mobile-nav.js",
                    "src/theme/js/jquery.gallery.js",
                    // "src/theme/js/jquery.bxslider.js",
                    "src/vendor/bxslider-4.2.12/dist/jquery.bxslider.js",
                    "src/theme/js/jquery.main.js"
                ],
                dest: 'web/public/assets/js/<%= pkg.name %>-pkgd.js'
            }
        },

        uglify: {
            options: {
                compress: {
                    warnings: false
                },
                mangle: true,
                maxLineLen: 3000,
                preserveComments: 'some'
            },
            target: {
                expand: true,
                cwd: 'web/public/assets/js/',
                src: ['*.js', '!*.min.js'],
                dest: 'web/public/assets/js/',
                ext: '.min.js'
            }
        },

        usebanner: {
            theme: {
                options: {
                    banner: '/*! Pillana(r)t Styles <%= builddata %>'
                },
                files: {
                    src: ['web/public/assets/css/*.css']
                }
            },
            members: {
                options: {
                    banner: '/*! Pillana(r)t Members and SEO <%= builddata %>'
                },
                files: {
                    src: ['web/public/app/plugins/pillanart-ms/**/*.css']
                }
            },
            plugin: {
                options: {
                    banner: '/*! Pillana(r)t Site Functionality <%= builddata %>'
                },
                files: {
                    src: ['web/public/app/plugins/site-functionality/**/*.css']
                }
            },
            js: {
                options: {
                    banner: '/*! Pillana(r)t Scripts <%= builddata %>'
                },
                files: {
                    src: ['web/public/assets/js/*.js']
                }
            }

        },

        watch: {
            less: {
                files: ['src/**/*.less'],
                tasks: 'buildcss'
            },
            js: {
                files: ['src/**/*.js'],
                tasks: 'buildjs'
            }
        }

    });

    // Register tasks.
    grunt.registerTask('default', 'watch');

    grunt.registerTask('build', ['buildcss', 'buildjs', 'wpthemedata']);

    grunt.registerTask('buildcss', ['theme', 'editor', 'plugins']);

    grunt.registerTask('buildjs', ['concat', 'uglify', 'usebanner:js']);

    grunt.registerTask('theme', [
            'less:theme',
            'autoprefixer:theme',
            'csscomb:theme',
            'cmq',
            'cssmin:theme',
            'usebanner:theme'
        ]);

    grunt.registerTask('editor', [
            'less:editor',
            'autoprefixer:editor',
            'csscomb:editor',
            'cssmin:editor'
        ]);

    grunt.registerTask('plugins', [
            'less:members', 'less:plugin',
            'autoprefixer:members', 'autoprefixer:plugin',
            'csscomb:members', 'csscomb:plugin',
            'cssmin:members', 'cssmin:plugin',
            'usebanner:members', 'usebanner:plugin'
        ]);

    grunt.registerTask('wpthemedata', 'Write WordPress theme header to style.css from package.json', function () {
            var file = grunt.template.process('web/public/app/themes/pillanart/style.css');
            var data = grunt.template.process('<%= wpthemedata %>');

            var chalk = require('chalk');
            grunt.file.write(file, data);
            grunt.log.writeln('WordPress theme header written to ' + chalk.blue(file) + '.');
        });

};
