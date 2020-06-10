let Encore = require('@symfony/webpack-encore')

Encore
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .setOutputPath('build/')
    .setPublicPath('/themes/custom/opdavies/build')

    .addEntry('app', './js/app.js')

    .enablePostCssLoader()

if (!Encore.isProduction()) {
    Encore.enableSourceMaps()
}

module.exports = Encore.getWebpackConfig()
