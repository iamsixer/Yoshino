package yoshino.services;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import yoshino.engine.StreamEngine;
import yoshino.errors.PageNotFoundException;
import yoshino.models.Channel;
import yoshino.models.User;
import yoshino.repositories.ChannelRepository;
import yoshino.repositories.UserRepository;
import yoshino.utils.Encode;

import java.util.Date;
import java.util.List;
import java.util.Map;

/**
 * Created by Volio on 2017/1/7.
 */
@Service
public class ChannelService {

    private final ChannelRepository channelRepository;
    private final UserRepository userRepository;
    private final StreamEngine streamEngine;

    @Autowired
    public ChannelService(ChannelRepository channelRepository, UserRepository userRepository, StreamEngine streamEngine) {
        this.channelRepository = channelRepository;
        this.userRepository = userRepository;
        this.streamEngine = streamEngine;
    }

    public Channel findOne(Long id) {
        return channelRepository.findOne(id);
    }

    public Channel findOne(User user) {
        return channelRepository.findOneByUser(user);
    }

    public Map<String, String> getPlayUrl(Long id) {
        Channel channel = channelRepository.findOne(id);
        if (channel == null) {
            throw new PageNotFoundException();
        }
        return streamEngine.getPlayUrl(channel.getStreamKey());
    }

    public Channel createNewChannel(String username) {
        User user = userRepository.findOneByUsername(username);

        if (user.isStreamer()) {
            return channelRepository.findOneByUser(user);
        }

        user.setStreamer(true);

        Channel channel = new Channel();
        channel.setName(user.getUsername());
        channel.setStreamKey(createStreamKey(user));
        channel.setUser(user);
        channel.setStreaming(false);
        channel.setCreatedAt(new Date());
        return channelRepository.save(channel);
    }

    public Channel updateStatus(String streamKey, String status) {
        Channel channel = channelRepository.findOneByStreamKey(streamKey);
        if (channel != null) {
            if (status.equals("connected"))
                channel.setStreaming(true);
            else
                channel.setStreaming(false);
            channelRepository.save(channel);
        }
        return channel;
    }

    public List<Channel> getLivingChannels() {
        return channelRepository.findAllByStreaming(true);
    }

    public Channel updateTitle(String username, String title) {
        User user = userRepository.findOneByUsername(username);
        Channel channel = channelRepository.findOneByUser(user);
        if (channel != null) {
            channel.setName(title);
            channelRepository.save(channel);
        }
        return channel;
    }

    private String createStreamKey(User user) {
        String streamKey;

        do {
            streamKey = Encode.string2MD5(user.getUsername() + System.currentTimeMillis());
            Channel channel = channelRepository.findOneByStreamKey(streamKey);
            if (channel != null) {
                streamKey = null;
            }
        } while (streamKey == null);

        return streamKey;
    }
}
