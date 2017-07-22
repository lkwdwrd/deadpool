/**
 * This file contains the webpack configuration.
 */

// Include dependencies
const path = require( 'path' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );

// Helper vars
const assets = path.resolve( __dirname, 'wp-content/themes/deadpool/assets' );

// Export config.
module.exports = {
	context: assets,
	entry: {
		main: [
			'./js/src/main.js',
			'./css/src/main.scss'
		]
	},
	output: {
		path: assets,
		filename: 'js/dist/[name].bundle.js'
	},
	devtool: 'source-map',
	module: {
		rules: [
			{
				test: /\.js$/,
				use: 'babel-loader',
				exclude: [/node_modules/]
			},
			{
				test: /\.(jpe?g|gif|png|svg|woff|woff2)$/,
				use: 'file-loader?emitFile=false&name=[path][name].[ext]'
			},
			{
				test: /\.scss$/,
				use: ExtractTextPlugin.extract( {
					fallback: 'style-loader',
					use: [{
						loader: 'css-loader',
						options: {minimize: true}
					}, 'postcss-loader', 'sass-loader'],
					publicPath: '../../'
				} )
			}
		]
	},
	plugins: [
		new ExtractTextPlugin( {
			filename: 'css/dist/[name].css',
			allChunks: true
		} ),
		new BrowserSyncPlugin( {
			host: 'deadpool.dev',
			port: 8080,
			open: 'external',
			proxy: 'http://deadpool.dev/'
		} )
	]
};
