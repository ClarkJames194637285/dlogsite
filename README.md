# Contributing to Dlog

1. 久慈要さんが<s>[Issueを作成](https://gitlab.com/dlog1/dlog/-/issues/new) して 、ClarkさんにIssueへアサインする。</s>別のツールでタスクをアサインする。
2. Clarkさんは<s>[Issue一覧](https://gitlab.com/dlog1/dlog/-/issues) から</s>タスクを頂いて、*devブランチをベースにして*、新たしい作業専用なブランチを作成して作業して、*devブランチまで*Merge Requestを作成する
3. <s>[Merge requests](https://gitlab.com/dlog1/dlog/-/merge_requests) から技術的にMerge requestsをレビューして、マージする</s>
4. 久慈要さんが②ステップのマージリクエストを同意する。
そのあと、自動的に[http://203.137.102.83:8000/dlogsite_dev](http://203.137.102.83:8000/dlogsite_dev) に②ステップのコードが反映されるようになります。
久慈要さんがチェックする。問題があればまた1から４まで繰り返す

## Guidelines

Before we look into how, here are the guidelines. If your Pull Requests fail
to pass these guidelines it will be declined and you will need to re-submit
when you’ve made the changes. This might sound a bit tough, but it is required
for us to maintain quality of the code-base.

### PHP Style

All code must meet the [codeigniter4/coding-standard](https://github.com/codeigniter4/coding-standard).
By running [fPHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) for all changed files before making a new commit.
EX: `./vendor/bin/phpcbf  --standard=vendor/codeigniter4/codeigniter4-standard/CodeIgniter4/ruleset.xml -np application/controllers/Register.php`

Install [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [codeigniter4/coding-standard](https://github.com/codeigniter4/coding-standard): `composer install`

### Compatibility
+ php 7.4
+ composer
+ mysql

## How-to Contribute
1. [Set up Git](https://help.github.com/en/articles/set-up-git) (Windows, Mac & Linux)
2. Go to the [Dlog repo](https://gitlab.com/dlog1/dlog)
3. Clone Dlog repo: `git clone git@gitlab.com:dlog1/dlog.git`
4. Checkout the "dev_*"(dev: dev_add_login) branch from the newest "dev". At this point you are ready to start making changes.
5. Fix existing bugs on the Issue tracker after taking a look to see nobody else is working on them.
6. [Commit](https://help.github.com/en/articles/adding-a-file-to-a-repository-using-the-command-line) the files.
Please ensure all the changed code must meet the php-style before making a new commit.
7. [Push](https://help.github.com/en/articles/pushing-to-a-remote) your working branch.
8. Ensure your working branch contains the newest "dev" coding before creating a pull request: `git pull origin dev`
9. [Create a pull request](https://gitlab.com/dlog1/dlog/-/merge_requests/new) 
with source branch is your working branch(ex: dev_add_login) to target branch is "dev" and Assignees is 久慈要さん and Reviewers is khoinv

### Keeping your "dev" branch up-to-date before contribute
1. `git checkout dev`
3. `git pull origin dev`

Now your "dev" branch is up-to-date. You can start to contribute something.
