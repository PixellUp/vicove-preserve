
# Bulgarian Jokes

A easy to use API endpoint providing access to more then 100k jokes in different categories


## Demo

[Tiradzhii jokes](https://1cdek6bg7g.execute-api.eu-central-1.amazonaws.com/category/%D0%A2%D0%B8%D1%80%D0%B0%D0%B4%D0%B6%D0%B8%D0%B8)


## Docs for the API

[API Docs](https://public-storage-folder.s3.amazonaws.com/vicove/index.html#/operations/get-jokes)


## API Reference

### Base url

```http
https://1cdek6bg7g.execute-api.eu-central-1.amazonaws.com
```

#### Get all jokes (10 per page)

```http
  GET /
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `cursor` | `string?` | **Optional**. Cursor hash for next paginated page |

#### Get all categories

```http
  GET /categories
```

#### Get all jokes for category (10 per page)

```http
  GET /category{category}
```

| Parameter | Type     | Description                                                                  |
| :-------- | :------- |:-----------------------------------------------------------------------------|
| `category` | `string` | **Required**. `title_bg` category name from `/categories` - Example: Китай |


## Tech Stack Requirements


* PHP 8.0+
* Composer
* NPM
* Serverless framework (for deploy)
* AWC CLI with default profile set

## Installation

Clone the project

```bash
  git clone https://github.com/PixellUp/vicove-preserve
```

Go to the project directory

```bash
  cd vicove-preserve
```

Install dependencies

```bash
  composer install
```

```bash
  npm install
```

```bash
  cp .env.example .env
```

## Deployment

To deploy this project run

```bash
  serverless deploy
```


## Acknowledgements

This won't be possible without help from

- [vicove.biz](http://www.vicove.biz/)
