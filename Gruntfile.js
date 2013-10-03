/**
 * This file is part of the Kappa/FileManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

/**
 * This file is part of the Kappa/Sandbox package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

module.exports = function(grunt) {
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-coffee');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.registerTask('default', ['coffee', 'less', 'uglify', 'cssmin']);
	grunt.registerTask('js', ['coffee', 'uglify']);
	grunt.registerTask('css', ['less', 'cssmin']);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		paths: {
			assets: 'client-side/assets',
			dist: 'client-side/dist',
			components: '<%= paths.assets %>/components'
		},
		uglify: {
			build: {
				expand: true,
				flatten: true,
				cwd: '<%= paths.dist %>/js',
				src: ['*.js'],
				dest: '<%= paths.dist %>/js',
				ext: '.js'
			}
		},
		cssmin: {
			build: {
				expand: true,
				flatten: true,
				cwd: '<%= paths.dist %>/css',
				src: ['*.css'],
				dest: '<%= paths.dist %>/css',
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
		}
	});
};