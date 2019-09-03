#Welcome to SEEDS INTERNSHIP 2019 SUMMER!

佐村木友紀さん、株式会社シーズへようこそ！  
2週間の間ですが、よろしくお願いいたします。  
今回のインターンシップはこのリポジトリを使って実習を進めていきます。  

## インターンの目標
今回はPHP入門からスタートということで、簡単なメールフォームのPHPプログラムを用いて、そこから一つのサービスを完成させることを目指します。  
最終的にWebサービスの定番のひとつ、「データベース(MySQL)と連携して、Web上でデータの閲覧や書き込みを行う」までを目標としてがんばっていきましょう！


## 開発環境導入
今回はDockerというコンテナツールを使って環境を構築します。  
Dockerを使うことで、  
・Mac本体のローカル環境をクリーンな状態で保てる  
・「コンテナ」という技術を使用しており、案件によって異なる環境が必要となる場合でも競合せずに開発することができる  
・チーム開発の際に開発環境を統一できるため、環境の違いによるエラーが発生しない  
というメリットがあります。
現在WEB業界でとても注目されているツールです。チーム開発などの際にもとても便利なので、ぜひKCGのプロジェクト演習などでも積極的に使ってみてください！  

### ダウンロード・インストール  

Macの場合、下記よりDockerをダウンロードし、インストールを行ってください。  
[https://docs.docker.com/docker-for-mac/install/](https://docs.docker.com/docker-for-mac/install/)

Windowsではdocker for windowsではなくdocker toolboxを使用します。  
[https://docs.docker.com/toolbox/toolbox_install_windows/](https://docs.docker.com/toolbox/toolbox_install_windows/)
```
docker toolbox のインストール  
https://docs.docker.com/toolbox/toolbox_install_windows/

インストール時の注意点として、「virtualbox をインストール」みたいな項目のチェックははずす。
既存があるとおかしくなって戻すのに大変だったのでほんと注意です。

toolboxはvirtualboxでlinuxを立ち上げて、そこにdockerがある、という形でwindowsからdockerが使える状態を作ってくれるツールです。
ですのでdocker-machineはvirtualbox内のいちインスタンスとなります。

使用方法
インストールされたKitematicを起動。
起動したら左下のdocker cli をクリックするとpower shellが開くので
そこからdocker-compose.ymlのディレクトリに移動して
各種docker-composeのコマンドを実行すれば基本的に動きます。
```
windowsでしっかり動作確認はしていないので、動作は保証できないです…ごめんなさい。  


### 立ち上げ
```bash
docker-compose up -d
```
  
コマンドライン(ターミナル)で現在のディレクトリにアクセスし、上記コマンドを実行することで、自動的にコンテナが作成されます。  
今回立ち上げられるサービスは  
- Webコンテナ(実際にWebサービスを動かすところ。apache,php等を実行するところです)  
- DBコンテナ(データベースを動かすところ。MySQLが入っています)  
以上の2つです。

立ち上げが完了し、  
[http://localhost:51010](http://localhost:51010)  
上記URLにアクセスして、お問い合わせフォームが表示されれば環境構築成功です！


## 実装を進めよう
環境が構築できれば実装を進めていきましょう！
