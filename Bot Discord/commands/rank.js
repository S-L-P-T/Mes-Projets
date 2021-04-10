const config = require('../config.json'),
    fs = require('fs'),
    Discord = require('discord.js')

module.exports = {
    run: async (message, args, client) => {
        let userInfo = client.db.ranking[message.author.id];
        let member = message.mentions.members.first();
        let embed = new Discord.MessageEmbed()
            .addField("Level", userInfo.level)
            .addField("XP", userInfo.xp + "/100")
            .setColor(0x4286f4)
        if (!member) return message.channel.send(embed)
        let memberInfo = client.db.ranking[message.author.id]
        let embed2 = new Discord.MessageEmbed()
            .addField("Level", memberInfo.level)
            .addField("XP", memberInfo.xp + "/100")
            .setColor(0x4286f4)
        message.channel.send(embed2)
    },
    name: 'rank',
    guildOnly: true,
    help: {
        description: 'Cette commande permet de voir votre rank.',
    }
}