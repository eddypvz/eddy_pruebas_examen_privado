const { defineConfig } = require('@vue/cli-service')
const path = require('path')

module.exports = defineConfig({
  transpileDependencies: true,
  outputDir: path.resolve(__dirname, '../public/'),
  pluginOptions: {
    dotenv: {
      // load all the path variables into the app, true by default, set to false to disable
      systemvars: true
    }
  }
})
