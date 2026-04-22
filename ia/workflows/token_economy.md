# Token Economy Workflow

## Rules for the agent
- Return only changed files
- Do not rewrite entire project for a small change
- Reuse helpers
- Summarize unchanged context
- Prefer diff-like updates in prose

## Recommended request pattern
1. Ask for one module only
2. Ask for file list first
3. Ask for code next
4. Ask for review last
