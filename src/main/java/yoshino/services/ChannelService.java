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

    public String getPublishUrl(String username) {
        User user = userRepository.findOneByUsername(username);
        if (user.isStreamer()) {
            Channel channel = channelRepository.findOneByUser(user);
            return streamEngine.getPublishUrl(channel.getStreamKey());
        } else {
            return null;
        }
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
