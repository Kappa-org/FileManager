/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was publicributed with this source code.
 */

/**
 * This file is part of the Kappa/Sandbox package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was publicributed with this source code.
 */

module.exports = function(grunt) {
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-coffee');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-copy');

	grunt.registerTask('default', ['coffee', 'less', 'concat']);
	grunt.registerTask('compile', ['coffee', 'less', 'cssmin', 'uglify']);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		paths: {
			assets: 'client-side/assets',
			public: 'client-side/public',
			components: '<%= paths.assets %>/components'
		},
		uglify: {
			build: {
				expand: true,
				flatten: true,
				cwd: '<%= paths.public %>/js',
				src: ['*.js'],
				dest: '<%= paths.public %>/js',
				ext: '.js'
			}
		},
		cssmin: {
			build: {
				expand: true,
				flatten: true,
				cwd: '<%= paths.public %>/css',
				src: ['*.css'],
				dest: '<%= paths.public %>/css',
				ext: '.css'
			}
		},
		coffee: {
			build: {
				options: {
					join: true
				},
				files: {
					'<%= paths.assets %>/js/<%= pkg.name %>.js': [
						'<%= paths.assets %>/coffee/bootstrap.coffee'
					]
				}
			}
		},
		less: {
			build: {
				files: {
					'<%= paths.assets %>/css/<%= pkg.name %>.css': [
						'<%= paths.assets %>/less/*.less',
						'!<%= paths.assets %>/less/_*.less'
					]
				}
			}
		},
		concat: {
			build: {
				files: {
					'<%= paths.public %>/js/<%= pkg.name %>.js': [
						'<%= paths.components %>/jquery/jquery.js',
						'<%= paths.components %>/bootstrap/bootstrap.js',
						'<%= paths.components %>/nette/nette.ajax.js',
						'<%= paths.components %>/nette/netteForms.js',
						'<%= paths.components %>/plupload/plupload.full.min.js',
						'<%= paths.assets %>/js/<%= pkg.name %>.js'
					]
				}
			}
		},
		copy: {
			build: {
				expand: true,
				cwd: '<%= paths.components %>/plupload/',
				src: '*',
				dest: '<%= paths.public %>/plupload'
			}
		}
	});
};