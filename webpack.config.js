const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath(__dirname+'/assets/build')
    .setPublicPath('/')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSassLoader()
    .disableSingleRuntimeChunk()
    .autoProvidejQuery()
    .addEntry('index', __dirname+'/assets/src/index.js')
;

module.exports = Encore.getWebpackConfig();
