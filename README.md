# [speechtext.ai](https://speechtext.ai)

Transcribe and translate audio files using OpenAI's Whisper API.

## Demo![gif](https://user-images.githubusercontent.com/82529756/227578172-23c46437-98eb-443d-8e95-b8f2a7eae6ed.gif)

## How it works

speechtext uses the recently released [OpenAI Whisper](https://platform.openai.com/docs/guides/speech-to-text) API to transcribe audio files.
You can upload any audio file, and the application will send it through the OpenAI Whisper API using Laravel's http client.
Translation makes use of the new [OpenAI Chat API](https://platform.openai.com/docs/guides/code)
## Running Locally

### Clone the repository

```bash
git clone https://github.com/chandachewe10/whisper-open-ai.git
```

### Create an OpenAI account and link your API key.

1. Sign up at [OpenAI](https://openai.com/) to create a free account (you'll get $18 credits)
2. Click on the "User" / "API Keys" menu item and create a new API key
3. Copy `.env.example` file to `.env` and Configure the `OPEN_AI_API_KEY` environment variable

