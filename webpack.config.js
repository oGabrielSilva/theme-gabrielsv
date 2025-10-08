const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

const themePath = path.resolve(__dirname, 'wp-content/themes/gabrielsv.com/resources');

module.exports = {
  entry: {
    // TypeScript files
    'javascript/main': path.join(themePath, 'typescript/main.ts'),
    'javascript/auth': path.join(themePath, 'typescript/auth.ts'),
    'javascript/profile': path.join(themePath, 'typescript/profile.ts'),
    'javascript/comments': path.join(themePath, 'typescript/comments.ts'),

    // CSS files
    'css/master': path.join(themePath, 'css/master.css'),
  },

  output: {
    path: path.resolve(__dirname, 'wp-content/themes/gabrielsv.com/resources/dist'),
    filename: '[name].min.js',
    clean: true,
  },

  resolve: {
    extensions: ['.ts', '.js'],
    alias: {
      '@': path.resolve(themePath, 'typescript'),
    },
  },

  module: {
    rules: [
      {
        test: /\.ts$/,
        use: 'ts-loader',
        exclude: /node_modules/,
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, 'css-loader'],
      },
    ],
  },

  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].min.css',
    }),
  ],

  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        extractComments: false,
        terserOptions: {
          format: {
            comments: false,
          },
        },
      }),
      new CssMinimizerPlugin(),
    ],
  },

  performance: {
    hints: false,
  },
};
