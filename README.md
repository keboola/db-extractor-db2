# DB2 DB Extractor
[![GitHub Actions](https://github.com/keboola/db-extractor-db2/actions/workflows/push.yml/badge.svg)](https://github.com/keboola/db-extractor-db2/actions/workflows/push.yml)

### Development

- Clone the repository.
- Create `.env` file with `AWS_ACCESS_KEY_ID` and `AWS_SECRET_ACCESS_KEY`.
- Run `docker-compose build`.

#### Tools

- codesniffer: `docker-compose run --rm app composer phpcs` 
- static analysis: `docker-compose run --rm app composer phpstan`
- unit tests: `docker-compose run --rm app composer tests`

#### Configuration Options

The configuration requires a `db` node with the following properties: 

- `host`: string (required) the hostname of the database
- `port`: numeric (required)
- `user`: string (required)
- `database`: string (required)
- `#password`: string (required)
- `ssh`: array (optional, but if present, enabled, keys/public, user, and sshHost are required)
  - `enabled`: boolean 
  - `keys`: array 
    - `#private`: string
    - `public`: string                
  - `user` string
  - `sshHost` string
  - `sshPort` string
   
There are 2 possible types of table extraction.  
1. A table defined by `schema` and `tableName`, this option can also include a columns list.
2. A `query` which is the SQL SELECT statement to be executed to produce the result table.

The extraction has the following configuration options:

- `name`: string (required),
- `query`: stirng (optional, but required if table not present)
- `table`: array (optional, but required if table not present)
  - `tableName`: string
  - `schema`: string
- `columns`: array of strings (only for table type configurations)
- `outputTable`: string (required)
- `incremental`: boolean (optional)
- `primaryKey`: array of strings (optional)
- `incrementalFetchingColumn`: string (optional)
- `incrementalFetchingLimit`: integer (optional)

## License

MIT licensed, see [LICENSE](./LICENSE) file.
