FROM node:20-slim

RUN addgroup --system --gid 1001 nodejs \
    && adduser --system --uid 1001 nextjs

WORKDIR /app

COPY package.json ./
RUN npm install --omit=dev

ENV NODE_ENV=production

COPY . .

USER nextjs

EXPOSE 3000

CMD ["node", "server.js"]
