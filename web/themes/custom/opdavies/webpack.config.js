let Encore = require('@symfony/webpack-encore')
let path = require('path')

Encore
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .setOutputPath('build/')
    .setPublicPath('/themes/custom/opdavies/build')

    .addEntry('app', '@/app.js')

    .enablePostCssLoader()

    .addAliases({
        '@': path.resolve(__dirname, 'assets', 'js'),
        styles: path.resolve(__dirname, 'assets', 'css')
    })

if (!Encore.isProduction()) {
    Encore.enableSourceMaps()
}

process.env.NODE_ENV =
  process.env.NODE_ENV || Encore.isProduction() ? 'production' : 'development';

module.exports = Encore.getWebpackConfig()
